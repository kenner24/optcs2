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

class SalesForceOpportunityService
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

    public function fetchAndSaveOpportunitiesData()
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

            $this->fetchAllOpportunitiesForUser($accessToken, $currentYear, $value['user_id']);
        }
    }

    /**
     * Fetch all opportunity from salesforce and dispatch a
     * ComputedAndSaveSalesForceDataJob job for further processing
     */
    public function fetchAllOpportunitiesForUser($token, $year, $userId, $nextCursor = null)
    {
        try {
            if ($nextCursor === null) {
                $result = Http::withToken($token)->acceptJson()->post($this->graphql_api_base_url, [
                    'query' => SalesForceGraphqlQueryEnum::GET_ALL_OPPORTUNITY_BY_YEAR_QUERY->value,
                    'variables' => [
                        'year' => $year
                    ]
                ])->json();

                if (Arr::has($result, "data.uiapi.query.Opportunity")) {
                    $data = $result["data"]["uiapi"]["query"]["Opportunity"];
                    ComputedAndSaveSalesForceDataJob::dispatch([
                        'type' => 'opportunity',
                        'userId' => $userId,
                        'data' => $data['edges']
                    ]);
                }
                if (Arr::has($result, "pageInfo.hasNextPage")) {
                    $this->fetchAllOpportunitiesForUser($token, $year, $userId, $data["pageInfo"]["endCursor"]);
                }
            } else {
                $result = Http::withToken($token)->acceptJson()->post($this->graphql_api_base_url, [
                    'query' => SalesForceGraphqlQueryEnum::GET_PAGINATED_OPPORTUNITY_DATA_QUERY->value,
                    'variables' => [
                        'after' => $nextCursor,
                        'year' => $year
                    ]
                ])->json();

                if (Arr::has($result, "data.uiapi.query.Opportunity")) {
                    $data = $result["data"]["uiapi"]["query"]["Opportunity"];
                    ComputedAndSaveSalesForceDataJob::dispatch([
                        'type' => 'opportunity',
                        'userId' => $userId,
                        'data' => $data['edges']
                    ]);
                }

                if (Arr::has($result, "pageInfo.hasNextPage")) {
                    $this->fetchAllOpportunitiesForUser($token, $year, $userId, $data["pageInfo"]["endCursor"]);
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
     * process the upcoming payload and save the data into the database
     */
    public function saveOpportunityData($payload)
    {
        foreach ($payload['data'] as $opportunityKey => $opportunityValue) {
            $this->opportunityService->updateOrCreate([
                'user_id' => $payload['userId'],
                'opportunity_id' => $opportunityValue["node"]["Id"],
                'name' => $opportunityValue["node"]["Name"]["value"],
                'source' => $opportunityValue["node"]["LeadSource"]["value"] ?? null,
                'stage' => $opportunityValue["node"]["StageName"]["value"] ?? null,
                'opportunity_created_date' => $opportunityValue["node"]["CreatedDate"]["value"],
                'opportunity_close_date' => $opportunityValue["node"]["CloseDate"]["value"],
                'amount' => $opportunityValue["node"]["Amount"]["value"],
                'expected_revenue' => $opportunityValue["node"]["ExpectedRevenue"]["value"],
            ]);
        }
    }
}
