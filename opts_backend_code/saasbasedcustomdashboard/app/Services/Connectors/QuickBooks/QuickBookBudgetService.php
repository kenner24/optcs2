<?php

namespace App\Services\Connectors\QuickBooks;

use App\Enums\QuickBooksApiListEnum;
use Illuminate\Support\Facades\Http;

class QuickBookBudgetService extends QuickBookBaseService
{

    public function fetchTheBudgetList($payload)
    {
        $accessToken = $payload->access_token;

        if (now()->diffInMinutes($payload->access_token_expired_at, false) < 5) {

            $response = $this->refreshToken(
                $payload["user_id"],
                $payload["refresh_token"],
                $payload["quick_book_realmId"]
            );

            $accessToken = $response->access_token;
        }

        $apiUrl = $this->getApiBaseUrl() . QuickBooksApiListEnum::QUERY_API->value;
        $result = Http::acceptJson()
            ->withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/text',
            ])
            ->withUrlParameters([
                'companyId' => $payload->quick_book_realmId
            ])
            ->get($apiUrl, [
                'query' => 'Select * from Budget',
                'minorversion' => $this->getMinorVersion(),
            ])
            ->json();

        if (!empty($result['QueryResponse']) && $result['QueryResponse']['totalCount'] > 0) {
            $budget = $result['QueryResponse']['Budget'];
            $budgetList = [];
            foreach ($budget as $key => $value) {
                array_push($budgetList, [
                    "name" => $value['Name'],
                    "budget_id" => $value['Id'],
                    "start_date" => $value['StartDate'],
                    "end_date" => $value['EndDate'],
                    "budget_type" => $value['BudgetType'],
                ]);
            }
            return $budgetList;
        }
        return [];
    }
}
