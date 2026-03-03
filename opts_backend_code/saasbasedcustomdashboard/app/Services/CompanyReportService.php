<?php

namespace App\Services;

use App\Enums\MonthEnum;
use App\Enums\ReportNameEnum;
use App\Models\User;

class CompanyReportService
{
    private $months = [];

    public function __construct(
        private User $user,
        private LeadService $leadService,
        private OpportunityService $opportunityService,
        private CashOnHandService $cashOnHandService,
        private LiabilityService $liabilityService,
        private ExpenseService $expenseService,
        private BudgetService $budgetService,
        private ConnectorPreferenceService $connectorPreferenceService,
        private ProfitabilityService $profitabilityService,
        private ReportGoalService $reportGoalService,
    ) {
        $this->months = MonthEnum::getAllValues();
    }

    /**
     * Fetch the data for the leads generated in the current and previous year
     * of the given company id
     * @param int $companyId
     * @param int $year
     */
    public function getLeadsGeneratedChartData($companyId, $year)
    {

        $currentYear = $year;
        $previousYear = now()->setYear($year)->subYear()->year;
        $currentYearRes = $this->leadService->getLeadsCountByYear($currentYear, $companyId);
        $prevYearRes = $this->leadService->getLeadsCountByYear($previousYear, $companyId);
        $currentYearTargetRes = $this->reportGoalService->findReportGoals(
            $currentYear,
            ReportNameEnum::Leads_Generated,
            $companyId
        );

        $dummyCurrentYearTarget = [];
        foreach ($this->months as $key => $value) {
            $tempCurr = $currentYearRes->firstWhere('month', ($key + 1));
            $tempPrev = $prevYearRes->firstWhere('month', ($key + 1));

            if (!$tempCurr) {
                $currentYearRes->push(
                    [
                        'year' => $currentYear,
                        'month' => $value,
                        'count' => 0,
                    ]
                );
            }

            if (!$tempPrev) {
                $prevYearRes->push(
                    [
                        'year' => $previousYear,
                        'month' => $value,
                        'count' => 0,
                    ]
                );
            }

            if ($currentYearTargetRes->count() === 0) {
                array_push($dummyCurrentYearTarget, [
                    'year' => $previousYear,
                    'month' => $value,
                    'value' => 0,
                ]);
            }
        }

        return [
            $currentYear => $currentYearRes->sortBy('month')->values()->all(),
            $previousYear => $prevYearRes->sortBy('month')->values()->all(),
            "current_year_target" => $currentYearTargetRes->count() > 0 ?
                $currentYearTargetRes :
                $dummyCurrentYearTarget
        ];
    }

    /**
     * Fetch the data for the opportunities generated in the current and previous year
     * of the given company id
     * @param int $companyId
     * @param int $year
     */
    public function getOpportunityChartData($companyId, $year)
    {

        $currentYear = $year;
        $previousYear = now()->setYear($year)->subYear()->year;
        $currentYearRes = $this->opportunityService->getOpportunityCountByYear($currentYear, $companyId);
        $prevYearRes = $this->opportunityService->getOpportunityCountByYear($previousYear, $companyId);
        $currentYearTargetRes = $this->reportGoalService->findReportGoals(
            $currentYear,
            ReportNameEnum::Open_Opportunities,
            $companyId
        );

        $dummyCurrentYearTarget = [];
        foreach ($this->months as $key => $value) {
            $tempCurr = $currentYearRes->firstWhere('month', ($key + 1));
            $tempPrev = $prevYearRes->firstWhere('month', ($key + 1));

            if (!$tempCurr) {
                $currentYearRes->push(
                    [
                        'year' => $currentYear,
                        'month' => $value,
                        'count' => 0,
                    ]
                );
            }

            if (!$tempPrev) {
                $prevYearRes->push(
                    [
                        'year' => $previousYear,
                        'month' => $value,
                        'count' => 0,
                    ]
                );
            }

            if ($currentYearTargetRes->count() === 0) {
                array_push($dummyCurrentYearTarget, [
                    'year' => $currentYear,
                    'month' => $value,
                    'value' => 0,
                ]);
            }
        }

        return [
            $currentYear => $currentYearRes->sortBy('month')->values()->all(),
            $previousYear => $prevYearRes->sortBy('month')->values()->all(),
            "current_year_target" => $currentYearTargetRes->count() > 0 ?
                $currentYearTargetRes : $dummyCurrentYearTarget
        ];
    }

    /**
     * Fetch the cash on hand data of the company in the current year
     * @param int $companyId
     * @param int $year
     */
    public function getCashOnHandChartData($companyId, $year)
    {

        $currentYear = $year;
        $currentYearRes = $this->cashOnHandService->getDataByYear($currentYear, $companyId);

        if ($currentYearRes->count() > 0) {
            return $currentYearRes;
        }

        $tempArray = [];
        foreach (MonthEnum::cases() as $monthIndex => $monthValue) {
            array_push($tempArray, [
                "year" => $currentYear,
                "month" => $monthValue->value,
                'amount' => 0,
                'currency' => null
            ]);
        }

        return $tempArray;
    }

    /**
     * Fetch the  liabilities data of the company in the current year and the last year
     * @param int $companyId
     * @param int $year
     */
    public function getLiabilitiesChartData($companyId, $year)
    {
        $currentYear = $year;
        $previousYear = now()->setYear($year)->subYear()->year;
        $currentYearRes = $this->liabilityService->getDataByYear($currentYear, $companyId);
        $prevYearRes = $this->liabilityService->getDataByYear($previousYear, $companyId);

        // check if data is present for both year if not add default values and send response
        if ($currentYearRes->count() > 0 && $prevYearRes->count() > 0) {
            return [
                $currentYear => $currentYearRes,
                $previousYear => $prevYearRes,
            ];
        }

        $tempCurrentYear = [];
        $tempPrevYear = [];
        foreach (MonthEnum::cases() as $monthIndex => $monthValue) {
            if ($currentYearRes->count() === 0) {
                array_push($tempCurrentYear, [
                    'year' => $currentYear,
                    'month' => $monthValue->value,
                    'amount' => 0,
                    'currency' => null
                ]);
            }

            if ($prevYearRes->count() === 0) {
                array_push($tempPrevYear, [
                    'year' => $previousYear,
                    'month' => $monthValue->value,
                    'amount' => 0,
                    'currency' => null
                ]);
            }
        }

        return [
            $currentYear => $currentYearRes->count() > 0 ? $currentYearRes : $tempCurrentYear,
            $previousYear => $prevYearRes->count() > 0 ? $prevYearRes : $tempPrevYear,
        ];
    }

    /**
     * Fetch the monthly expenses data of the company
     * @param int $companyId (userId)
     * @param int $year
     */
    public function getMonthlyExpenseChartData($companyId, $year)
    {
        $currentYear = $year;
        $prevYear = now()->setYear($year)->subYear()->year;
        $budgetDetails = $this->connectorPreferenceService->getUserBudgetPreference($companyId, $currentYear);

        $currentYearExpenseRes = collect();
        $prevYearExpenseRes = collect();
        $currentYearBudgetRes = collect();
        if ($budgetDetails !== null) {
            $currentYearExpenseRes = $this->expenseService->getDataByYear($currentYear, $companyId);
            $prevYearExpenseRes = $this->expenseService->getDataByYear($prevYear, $companyId);
            $currentYearBudgetRes = $this->budgetService->getDataByYear(
                $currentYear,
                $companyId,
                $budgetDetails->field_value
            );

            if (
                $currentYearExpenseRes->count() > 0 &&
                $currentYearBudgetRes->count() > 0 &&
                $prevYearExpenseRes->count() > 0
            ) {
                return [
                    "expenses" => [
                        $currentYear => $currentYearExpenseRes,
                        $prevYear => $prevYearExpenseRes
                    ],
                    "budget" => $currentYearBudgetRes,
                ];
            }
        }

        $budget = [];
        $currTempExpense = [];
        $prevTempExpense = [];
        foreach (MonthEnum::cases() as $monthIndex => $monthValue) {

            if ($currentYearExpenseRes->count() === 0) {
                array_push($currTempExpense, [
                    "year" => $currentYear,
                    "month" => $monthValue->value,
                    "amount" => 0,
                    "currency" => null
                ]);
            }

            if ($prevYearExpenseRes->count() === 0) {
                array_push($prevTempExpense, [
                    "year" => $prevYear,
                    "month" => $monthValue->value,
                    "amount" => 0,
                    "currency" => null
                ]);
            }

            if ($currentYearBudgetRes->count() === 0) {
                array_push($budget, [
                    "year" => $currentYear,
                    "month" => $monthValue->value,
                    "amount" => 0,
                    "currency" => null
                ]);
            }
        }

        return [
            "expenses" => [
                $currentYear => $currentYearExpenseRes->count() > 0 ? $currentYearExpenseRes : $currTempExpense,
                $prevYear => $prevYearExpenseRes->count() > 0 ? $prevYearExpenseRes : $prevTempExpense
            ],
            "budget" => $currentYearBudgetRes->count() > 0 ? $currentYearBudgetRes :  $budget,
        ];
    }

    /**
     * Fetch the profitability percentage data of the company
     * @param int $companyId (userId)
     * @param int $year
     */
    public function getProfitabilityPercentageChartData($companyId, $year)
    {

        $currentYear = $year;
        $previousYear = now()->setYear($year)->subYear()->year;
        $currentYearRes = $this->profitabilityService->getDataByYear($currentYear, $companyId);
        $previousYearRes = $this->profitabilityService->getDataByYear($previousYear, $companyId);
        $targetedGoals = $this->reportGoalService->findReportGoals(
            $currentYear,
            ReportNameEnum::Profitability_Percentage,
            $companyId
        );

        if (
            $currentYearRes->count() > 0 &&
            $previousYearRes->count() > 0 &&
            $targetedGoals->count() > 0
        ) {
            return [
                $currentYear => $currentYearRes,
                $previousYear => $previousYearRes,
                "goal" => $targetedGoals
            ];
        }

        $dummyCurrYearRes = [];
        $dummyPrevYearRes = [];
        $dummyGoalData = [];
        foreach (MonthEnum::cases() as $monthIndex => $monthValue) {

            if ($currentYearRes->count() === 0) {
                array_push($dummyCurrYearRes, [
                    "year" => $currentYear,
                    "month" => $monthValue->value,
                    "percentage" => 0
                ]);
            }

            if ($previousYearRes->count() === 0) {
                array_push($dummyPrevYearRes, [
                    "year" => $previousYear,
                    "month" => $monthValue->value,
                    "percentage" => 0
                ]);
            }

            if ($targetedGoals->count() === 0) {
                array_push($dummyGoalData, [
                    "year" => $currentYear,
                    "month" => $monthValue->value,
                    "value" => 0
                ]);
            }
        }

        return [
            $currentYear => $currentYearRes->count() > 0 ? $currentYearRes : $dummyCurrYearRes,
            $previousYear => $previousYearRes->count() > 0 ? $previousYearRes : $dummyPrevYearRes,
            "goal" => $previousYearRes->count() > 0 ? $targetedGoals : $dummyGoalData,
        ];
    }
}
