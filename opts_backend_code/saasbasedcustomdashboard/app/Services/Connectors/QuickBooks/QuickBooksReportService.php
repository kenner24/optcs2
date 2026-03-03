<?php

namespace App\Services\Connectors\QuickBooks;

use App\Enums\MonthEnum;
use App\Enums\QuickBooksApiListEnum;
use App\Services\BudgetService;
use App\Services\CashOnHandService;
use App\Services\ConnectorAccessTokenService;
use App\Services\ConnectorPreferenceService;
use App\Services\ExpenseService;
use App\Services\LiabilityService;
use App\Services\ProfitabilityService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuickBooksReportService extends QuickBookBaseService
{
    public function __construct(
        private ConnectorAccessTokenService $connectorAccessTokenService,
        private CashOnHandService $cashOnHandService,
        private LiabilityService $liabilityService,
        private ExpenseService $expenseService,
        private ConnectorPreferenceService $connectorPreferenceService,
        private BudgetService $budgetService,
        private ProfitabilityService $profitabilityService
    ) {
        parent::__construct();
    }

    /**
     * Fetch the data for the multiple reports for the a user
     * and saved the computed data in the database
     * @param string $period (allowed values 'month', 'year')
     */
    public function fetchAndSaveReportDataForAllUsers($period = 'month')
    {
        $usersList = $this->connectorAccessTokenService->getAllUserQuickBookTokens();
        foreach ($usersList as $userKey => $userVal) {
            try {
                // in case of month we will fetch the current year data
                if ($period === 'month') {
                    $this->fetchSaveReportDataForUser($userVal, now());
                }

                // in case of year we only have to fetch the last 2 years data
                if ($period === 'year') {
                    $tempDate = now();
                    for ($i = 0; $i < 2; $i++) {
                        $this->fetchSaveReportDataForUser($userVal, $tempDate);
                        $tempDate = $tempDate->subYear();
                    }
                }
            } catch (\Exception $th) {
                Log::error(
                    'Error in ' . __CLASS__ . ' class inside the fetchAndSaveReportDataForAllUsers() method \n Error Message: ' . $th->getMessage()
                );
                continue;
            }
        }
    }


    /**
     * Fetch and save the report data for a single user
     * @param array $quickBookToken
     * @param Carbon\Carbon $date
     */
    public function fetchSaveReportDataForUser($quickBookToken, $date)
    {
        $result = $this->refreshToken(
            $quickBookToken["user_id"],
            $quickBookToken["refresh_token"],
            $quickBookToken["quick_book_realmId"]
        );

        $this->profitLossReport(
            [
                'realmId' => $quickBookToken["quick_book_realmId"],
                'token' => $result["access_token"],
                'start_date' => $date->startOfYear()->format('Y-m-d'),
                'end_date' => $date->endOfYear()->format('Y-m-d'),
                'year' => $date->format('Y'),
                'user_id' => $quickBookToken["user_id"],
            ]
        );

        $this->balanceSheetReport(
            [
                'realmId' => $quickBookToken["quick_book_realmId"],
                'token' => $result["access_token"],
                'start_date' => $date->startOfYear()->format('Y-m-d'),
                'end_date' => $date->endOfYear()->format('Y-m-d'),
                'year' => $date->format('Y'),
                'user_id' => $quickBookToken["user_id"],
            ]
        );

        $this->budgetData([
            'realmId' => $quickBookToken["quick_book_realmId"],
            'token' => $result["access_token"],
            'user_id' => $quickBookToken["user_id"],
            'year' => $date->format('Y'),
        ]);
    }


    /**
     * Fetch the data of the quick books profit loss api
     * @param array $payload
     */
    private function profitLossReport($payload)
    {
        $apiUrl = $this->getApiBaseUrl() . QuickBooksApiListEnum::PROFIT_AND_LOSS_REPORT_API->value;
        $result = Http::withToken($payload['token'])
            ->withUrlParameters([
                "companyId" => $payload['realmId']
            ])
            ->get($apiUrl, [
                "minorversion" => $this->getMinorVersion(),
                "summarize_column_by" => "Month",
                "start_date" => $payload['start_date'],
                "end_date" => $payload['end_date'],
            ])
            ->json();

        if (
            $result['Header']['Option'][1]['Name'] === 'NoReportData'
            &&
            $result['Header']['Option'][1]['Value'] === 'false'
        ) {
            $this->saveExpensesData(
                $result,
                $payload['year'],
                $payload['user_id']
            );
            $this->saveProfitabilityData(
                $result,
                $payload['year'],
                $payload['user_id']
            );
        }
    }

    /**
     * Save the expenses data in to the database
     * Formula: (Sum of expenses + sum of other expenses)
     */
    private function saveExpensesData(
        $result,
        $year,
        $userId
    ) {
        try {
            $expenses = [];
            $otherExpenses = [];
            $currency = $result['Header']['Currency'] ?? null;
            $expensesIndex = array_search("Expenses", array_column($result['Rows']['Row'], "group"));
            $otherExpensesIndex = array_search("OtherExpenses", array_column($result['Rows']['Row'], "group"));

            if (
                is_numeric($expensesIndex) &&
                Arr::has($result['Rows']['Row'][$expensesIndex], 'Summary.ColData')
            ) {
                $expenses = $result['Rows']['Row'][$expensesIndex]['Summary']['ColData'];
            }

            if (
                is_numeric($otherExpensesIndex) &&
                Arr::has($result['Rows']['Row'][$otherExpensesIndex], 'Summary.ColData')
            ) {
                $otherExpenses = $result['Rows']['Row'][$otherExpensesIndex]['Summary']['ColData'];
            }

            foreach (MonthEnum::cases() as $monthIndex => $monthValue) {
                $expensesVal = $expenses[$monthIndex + 1]['value'] ?? 0;
                $otherExpensesVal = $otherExpenses[$monthIndex + 1]['value'] ?? 0;
                $totalExpense = $expensesVal + $otherExpensesVal;

                $this->expenseService->updateOrCreate([
                    "month" => $monthValue->value,
                    "amount" => is_float($totalExpense) ? $totalExpense : 0,
                    "year" => $year,
                    "user_id" => $userId,
                    'currency' => $currency
                ]);
            }
        } catch (\Exception $th) {
            Log::error(
                'Error in ' . __FUNCTION__ . ' function \n Error Message: ' . $th->getMessage(),
            );
            return;
        }
    }

    /**
     * Save the profitability data to database
     * Formula: ((Sum of the total income - Sum of total expenses) / Sum of the total Income) * 100
     */
    private function saveProfitabilityData(
        $result,
        $year,
        $userId
    ) {
        try {
            $expenses = [];
            $otherExpenses = [];
            $income = [];
            $incomeIndex = array_search("Income", array_column($result['Rows']['Row'], "group"));
            $expensesIndex = array_search("Expenses", array_column($result['Rows']['Row'], "group"));
            $otherExpensesIndex = array_search("OtherExpenses", array_column($result['Rows']['Row'], "group"));

            // total income
            if (
                is_numeric($incomeIndex) &&
                Arr::has($result['Rows']['Row'][$incomeIndex], 'Summary.ColData')
            ) {
                $income = $result['Rows']['Row'][$incomeIndex]['Summary']['ColData'];
            }

            // expenses
            if (
                is_numeric($expensesIndex) &&
                Arr::has($result['Rows']['Row'][$expensesIndex], 'Summary.ColData')
            ) {
                $expenses = $result['Rows']['Row'][$expensesIndex]['Summary']['ColData'];
            }

            // other expenses
            if (
                is_numeric($otherExpensesIndex) &&
                Arr::has($result['Rows']['Row'][$otherExpensesIndex], 'Summary.ColData')
            ) {
                $otherExpenses = $result['Rows']['Row'][$otherExpensesIndex]['Summary']['ColData'];
            }

            foreach (MonthEnum::cases() as $monthIndex => $monthValue) {
                $expensesVal = $expenses[$monthIndex + 1]['value'] ?? 0;
                $otherExpensesVal = $otherExpenses[$monthIndex + 1]['value'] ?? 0;
                $totalIncomeVal = $income[$monthIndex + 1]['value'] ?? 0;
                $totalExpenseVal = $expensesVal + $otherExpensesVal;
                $profitabilityPercentage = $totalIncomeVal == 0 ? 0 : round(
                    (($totalIncomeVal - $totalExpenseVal) / $totalIncomeVal) * 100,
                    2
                );
                $this->profitabilityService->updateOrCreate([
                    "month" => $monthValue->value,
                    "percentage" => $profitabilityPercentage,
                    "year" => $year,
                    "user_id" => $userId,
                ]);
            }
        } catch (\Exception $th) {
            Log::error(
                'Error in ' . __FUNCTION__ . ' function \n Error Message: ' . $th->getMessage(),
            );
            return;
        }
    }

    /**
     * Fetch the data of the quick books balance sheet report
     * @param array $payload
     */
    private function balanceSheetReport($payload)
    {
        try {
            $apiUrl = $this->getApiBaseUrl() . QuickBooksApiListEnum::BALANCE_SHEET_REPORT->value;
            $result = Http::withToken($payload['token'])
                ->withUrlParameters([
                    "companyId" => $payload['realmId']
                ])
                ->get($apiUrl, [
                    "minorversion" => $this->getMinorVersion(),
                    "summarize_column_by" => "Month",
                    "start_date" => $payload['start_date'],
                    "end_date" => $payload['end_date'],
                ])
                ->json();

            $currency = $result['Header']['Currency'] ?? null;

            if (
                $result['Header']['Option'][1]['Name'] === 'NoReportData'
                &&
                $result['Header']['Option'][1]['Value'] === 'false'
            ) {

                // get the cash data from the response
                $bankAccountData = $this->findCashOnHandData($result);

                // get the liabilities data from the response
                $currentLiabilities = $this->findLiabilitiesData($result);

                foreach (MonthEnum::cases() as $monthIndex => $monthValue) {
                    $this->cashOnHandService->updateOrCreate([
                        "month" => $monthValue->value,
                        "amount" => $bankAccountData[$monthIndex + 1]['value'] ?? 0,
                        "year" => $payload['year'],
                        "user_id" => $payload['user_id'],
                        'currency' => $currency,
                    ]);

                    $this->liabilityService->updateOrCreate([
                        "month" => $monthValue->value,
                        "amount" => $currentLiabilities[$monthIndex + 1]['value'] ?? 0,
                        "year" => $payload['year'],
                        "user_id" => $payload['user_id'],
                        'currency' => $currency,
                    ]);
                }
            }
        } catch (\Exception $th) {
            Log::error(
                'Error in ' . __FUNCTION__ . ' function \n Error Message: ' . $th->getMessage(),
            );
            return;
        }
    }


    /**
     * Search and return the total bank accounts data array from the
     * data received from quickbooks for the cash on hand report
     */
    private function findCashOnHandData($result)
    {
        $bankAccountData = [];
        $totalAssetIndex = array_search("TotalAssets", array_column($result['Rows']['Row'], "group"));
        $bankAccountIndex = array_search("BankAccounts", array_column($result['Rows']['Row'], "group"));

        if (
            is_numeric($bankAccountIndex) &&
            Arr::has($result['Rows']['Row'][$bankAccountIndex], 'Summary.ColData')
        ) {
            return $result['Rows']['Row'][$bankAccountIndex]['Summary']['ColData'];
        }

        if (
            is_numeric($totalAssetIndex) &&
            Arr::has($result['Rows']['Row'][$totalAssetIndex], 'Rows.Row')
        ) {
            $totalAssetsData = $result['Rows']['Row'][$totalAssetIndex]['Rows']['Row'];
            $bankAccountIndex = array_search("BankAccounts", array_column($totalAssetsData, "group"));

            if (
                is_numeric($bankAccountIndex) &&
                Arr::has($totalAssetsData[$bankAccountIndex], 'Summary.ColData')
            ) {
                $bankAccountData = $totalAssetsData[$bankAccountIndex]['Summary']['ColData'];
            }

            $currentAssetIndex = array_search("CurrentAssets", array_column($totalAssetsData, "group"));

            if (
                is_numeric($currentAssetIndex) &&
                Arr::has($totalAssetsData[$currentAssetIndex], 'Rows.Row')
            ) {
                $currentAssetsData = $totalAssetsData[$currentAssetIndex]['Rows']['Row'];
                $bankAccountIndex = array_search("BankAccounts", array_column($currentAssetsData, "group"));

                if (
                    is_numeric($bankAccountIndex) &&
                    Arr::has($currentAssetsData[$bankAccountIndex], 'Summary.ColData')
                ) {
                    $bankAccountData = $currentAssetsData[$bankAccountIndex]['Summary']['ColData'];
                }
            }
        }

        return $bankAccountData;
    }

    /**
     * Search amd return the current liabilities data from the data
     * received from the quickbooks for the current liabilities chart
     */
    private function findLiabilitiesData($result)
    {
        $currentLiabilitiesData = [];
        $totalLiabilitiesAndEquityIndex = array_search(
            "TotalLiabilitiesAndEquity",
            array_column($result['Rows']['Row'], "group")
        );
        $currentLiabilityIndex = array_search(
            "CurrentLiabilities",
            array_column($result['Rows']['Row'], "group")
        );

        if (
            is_numeric($currentLiabilityIndex) &&
            Arr::has($result['Rows']['Row'][$currentLiabilityIndex], 'Summary.ColData')
        ) {
            return $result['Rows']['Row'][$currentLiabilityIndex]['Summary']['ColData'];
        }

        if (
            is_numeric($totalLiabilitiesAndEquityIndex) &&
            Arr::has($result['Rows']['Row'][$totalLiabilitiesAndEquityIndex], 'Rows.Row')
        ) {
            $totalLiabilityAndEquityData = $result['Rows']['Row'][$totalLiabilitiesAndEquityIndex]['Rows']['Row'];
            $currentLiabilityIndex = array_search(
                "CurrentLiabilities",
                array_column($totalLiabilityAndEquityData, "group")
            );
            $liabilitiesIndex = array_search(
                "Liabilities",
                array_column($totalLiabilityAndEquityData, "group")
            );

            if (
                is_numeric($currentLiabilityIndex) &&
                Arr::has($totalLiabilityAndEquityData[$currentLiabilityIndex], 'Summary.ColData')
            ) {
                $currentLiabilitiesData = $totalLiabilityAndEquityData[$currentLiabilityIndex]['Summary']['ColData'];
            }

            if (
                is_numeric($liabilitiesIndex) &&
                Arr::has($totalLiabilityAndEquityData[$liabilitiesIndex], 'Rows.Row')
            ) {
                $liabilitiesData = $totalLiabilityAndEquityData[$liabilitiesIndex]['Rows']['Row'];
                $currentLiabilityIndex = array_search(
                    "CurrentLiabilities",
                    array_column($liabilitiesData, "group")
                );

                if (
                    is_numeric($currentLiabilityIndex) &&
                    Arr::has($liabilitiesData[$currentLiabilityIndex], 'Summary.ColData')
                ) {
                    $currentLiabilitiesData = $liabilitiesData[$currentLiabilityIndex]['Summary']['ColData'];
                }
            }
        }

        return $currentLiabilitiesData;
    }

    /**
     * Fetch the budget data and save in database
     *
     * Formula: Sum of (cost of goods sold + expenses + other expenses) 
     * @param array $payload
     */
    private function budgetData($payload)
    {
        try {
            // Get the budget details saved by the user
            $budgetDetails = $this->connectorPreferenceService->getUserBudgetPreference(
                $payload['user_id'],
                $payload['year'],
            );
            if ($budgetDetails !== null) {
                $apiUrl = $this->getApiBaseUrl() . QuickBooksApiListEnum::READ_BUDGET_DETAIL_API->value;
                $result = Http::acceptJson()
                    ->withToken($payload['token'])
                    ->withHeaders([
                        'Content-Type' => 'application/text',
                    ])
                    ->withUrlParameters([
                        'companyId' => $payload['realmId'],
                        'budgetId' => $budgetDetails->field_value
                    ])
                    ->get($apiUrl, [
                        'minorversion' => $this->getMinorVersion(),
                    ])
                    ->json();
                $accountIds = $this->getAccountIdsForBudgetComputation(
                    $payload['token'],
                    $payload['realmId']
                );

                if (count($accountIds) > 0) {
                    $currency = null;
                    $budgetDetailByDate = collect($result['Budget']['BudgetDetail'])
                        ->filter(function ($value, $key) use ($accountIds) {
                            return in_array($value['AccountRef']['value'], $accountIds);
                        })
                        ->groupBy('BudgetDate');

                    foreach ($budgetDetailByDate as $date => $budget) {
                        $sum = $budget->sum(function ($item) {
                            return $item['Amount'];
                        });

                        $budgetDate = Carbon::parse($date);
                        $this->budgetService->updateOrCreate([
                            'user_id' => $payload['user_id'],
                            'budget_id' => $budgetDetails->field_value,
                            'amount' =>  $sum,
                            'year' => $budgetDate->format('Y'),
                            'month' => $budgetDate->format('M'),
                            'currency' => $currency
                        ]);
                    }
                }
            }
        } catch (\Exception $th) {
            Log::error(
                'Error in ' . __FUNCTION__ . ' function \n Error Message: ' . $th->getMessage(),
            );
            return;
        }
    }

    /**
     * This function will find the ids for the expenses, other expenses,
     * Cost of Goods Sold which will be used used in the budget computation
     */
    private function getAccountIdsForBudgetComputation(
        $accessToken,
        $realmId
    ) {
        $apiUrl = $this->getApiBaseUrl() . QuickBooksApiListEnum::QUERY_API->value;
        $result = Http::acceptJson()
            ->withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/text',
            ])
            ->withUrlParameters([
                'companyId' => $realmId
            ])
            ->get($apiUrl, [
                'query' => 'Select * from Account',
                'minorversion' => $this->getMinorVersion(),
            ])
            ->json();

        if (!empty($result['QueryResponse']) && $result['QueryResponse']['maxResults'] > 0) {
            $accounts = $result['QueryResponse']['Account'];
            $arr = [
                'Expense',
                'Cost of Goods Sold',
                'Other Expense'
            ];
            $filterData = Arr::where($accounts, function ($value, $key) use ($arr) {
                return in_array(
                    $value['Classification'],
                    $arr
                ) ||
                    in_array(
                        $value['AccountType'],
                        $arr
                    );
            });
            return Arr::pluck($filterData, 'Id');
        } else {
            return [];
        }
    }
}
