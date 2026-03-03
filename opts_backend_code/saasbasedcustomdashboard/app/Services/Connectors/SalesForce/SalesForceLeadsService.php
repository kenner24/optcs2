<?php

namespace App\Services\Connectors\SalesForce;

use App\Enums\SalesForceGraphqlQueryEnum;
use App\Jobs\ComputedAndSaveSalesForceDataJob;
use App\Services\ConnectorAccessTokenService;
use App\Services\Connectors\SalesForce\SalesForceAuthService;
use App\Services\LeadService;
use App\Services\OpportunityService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SalesForceLeadsService
{
    private $api_query_all_base_url = null;
    private $api_query_base_url = null;
    private $graphql_api_base_url = null;

    public function __construct(
        private ConnectorAccessTokenService $connectorAccessTokenService,
        private SalesForceAuthService $salesForceAuthService,
        private LeadService $leadService,
        private OpportunityService $opportunityService
    ) {
        $base_url = config('salesforce.api_base_url');
        $version = config('salesforce.api_version');
        $this->api_query_all_base_url = "{$base_url}services/data/{$version}/queryAll";
        $this->api_query_base_url = "{$base_url}services/data/{$version}/query";
        $this->graphql_api_base_url = "{$base_url}services/data/{$version}/graphql";
    }

    public function fetchAndSaveLeadsData()
    {
        $currentYear = now()->year;
        $result = $this->connectorAccessTokenService->getAllUserSalesForceToken();
        foreach ($result as $key => $value) {
            $accessToken = $value->access_token;
            $introspectionRes = $this->salesForceAuthService->tokenIntrospection($accessToken);

            if (!$introspectionRes) {
                $refreshTokenRes = $this->salesForceAuthService->refreshToken($value->refresh_token, $value->user_id);
                if ($refreshTokenRes['success']) {
                    $accessToken = $refreshTokenRes['token'];
                } else {
                    continue;
                }
            }

            $this->fetchAllLeadsForUser($accessToken, $currentYear, $value['user_id']);
        }
    }

    /**
     * Fetch the data from the salesforce for the lead and pass the data to the
     * ComputedAndSaveSalesForceDataJob job
     * 
     */
    public function fetchAllLeadsForUser($token, $year, $userId, $nextCursor = null)
    {
        try {
            if ($nextCursor === null) {
                $result = Http::withToken($token)->acceptJson()->post($this->graphql_api_base_url, [
                    'query' => SalesForceGraphqlQueryEnum::GET_ALL_LEADS_BY_YEAR_QUERY->value,
                    'variables' => [
                        'year' => $year
                    ]
                ])->json();

                if (Arr::has($result, "data.uiapi.query.Lead")) {
                    $data = $result["data"]["uiapi"]["query"]["Lead"];
                    ComputedAndSaveSalesForceDataJob::dispatch([
                        'type' => 'lead',
                        'userId' => $userId,
                        'data' => $data['edges']
                    ]);
                }


                if (Arr::has($result, "pageInfo.hasNextPage")) {
                    $this->fetchAllLeadsForUser($token, $year, $userId, $data["pageInfo"]["endCursor"]);
                }
            } else {
                $result = Http::withToken($token)->acceptJson()->post($this->graphql_api_base_url, [
                    'query' => SalesForceGraphqlQueryEnum::GET_PAGINATED_LEAD_DATA_QUERY->value,
                    'variables' => [
                        'after' => $nextCursor,
                        'year' => $year
                    ]
                ])->json();

                if (Arr::has($result, "data.uiapi.query.Lead")) {
                    $data = $result["data"]["uiapi"]["query"]["Lead"];
                    ComputedAndSaveSalesForceDataJob::dispatch([
                        'type' => 'lead',
                        'userId' => $userId,
                        'data' => $data['edges']
                    ]);
                }

                if (Arr::has($result, "pageInfo.hasNextPage")) {
                    $this->fetchAllLeadsForUser($token, $year, $userId, $data["pageInfo"]["endCursor"]);
                }
            }
            return;
        } catch (\Exception $th) {
            Log::error(
                'Error in ' . __FUNCTION__ . ' function \n Error Message: ' . $th->getMessage(),
            );
            return;
        }
    }

    /**
     * compute and save the lead data
     */
    public function saveLeadData($payload)
    {
        foreach ($payload['data'] as $leadKey => $leadValue) {
            $this->leadService->updateOrCreate([
                'user_id' => $payload['userId'],
                'lead_id' => $leadValue["node"]["Id"],
                'name' => $leadValue["node"]["Name"]["value"],
                'status' => $leadValue["node"]["Status"]["value"],
                'source' => $leadValue["node"]["LeadSource"]["value"],
                'lead_created_date' => $leadValue["node"]["CreatedDate"]["value"],
                'lead_converted_date' => $leadValue["node"]["ConvertedDate"]["value"],
                'annual_revenue' => $leadValue["node"]["AnnualRevenue"]["value"],
            ]);
        }
    }
}
