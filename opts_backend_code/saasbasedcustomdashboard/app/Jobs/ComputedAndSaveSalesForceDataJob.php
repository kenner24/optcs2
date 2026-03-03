<?php

namespace App\Jobs;

use App\Services\Connectors\SalesForce\SalesForceLeadsService;
use App\Services\Connectors\SalesForce\SalesForceOpportunityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ComputedAndSaveSalesForceDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload = null;
    public $tries = 5;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->payload['type'] === 'opportunity') {
            $this->salesForceOpportunityService()->saveOpportunityData($this->payload);
        }

        if ($this->payload['type'] === 'lead') {
            $this->salesForceLeadService()->saveLeadData($this->payload);
        }
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
    private function salesForceLeadService(): SalesForceLeadsService
    {
        return App::make(SalesForceLeadsService::class);
    }
}
