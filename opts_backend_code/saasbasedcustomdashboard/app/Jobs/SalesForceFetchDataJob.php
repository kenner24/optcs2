<?php

namespace App\Jobs;

use App\Models\ConnectorAccessToken;
use App\Services\Connectors\SalesForce\SalesForceAuthService;
use App\Services\Connectors\SalesForce\SalesForceLeadsService;
use App\Services\Connectors\SalesForce\SalesForceOpportunityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class SalesForceFetchDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    
    /**
     * Create a new job instance.
     */
    public function __construct(
        private ConnectorAccessToken $connectorAccessToken,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentYear = now()->year;
        $accessToken = $this->connectorAccessToken->access_token;
        $introspectionRes = $this->salesForceAuthService()->tokenIntrospection($accessToken);

        if (!$introspectionRes) {
            $refreshTokenRes = $this->salesForceAuthService()->refreshToken(
                $this->connectorAccessToken->refresh_token,
                $this->connectorAccessToken->user_id
            );
            if ($refreshTokenRes['success']) {
                $accessToken = $refreshTokenRes['token'];
            }
        }

        $this->salesForceOpportunityService()->fetchAllOpportunitiesForUser(
            $accessToken,
            $currentYear,
            $this->connectorAccessToken->user_id
        );
        $this->salesForceLeadsService()->fetchAllLeadsForUser(
            $accessToken,
            $currentYear,
            $this->connectorAccessToken->user_id
        );
    }

    /**
     * Get an instance of the SalesForceAuthService from the service container.
     */
    private function salesForceAuthService(): SalesForceAuthService
    {
        return App::make(SalesForceAuthService::class);
    }

    /**
     * Get an instance of the SalesForceOpportunityService from the service container.
     */
    private function salesForceOpportunityService(): SalesForceOpportunityService
    {
        return App::make(SalesForceOpportunityService::class);
    }

    /**
     * Get an instance of the SalesForceOpportunityService from the service container.
     */
    private function salesForceLeadsService(): SalesForceLeadsService
    {
        return App::make(SalesForceLeadsService::class);
    }
}
