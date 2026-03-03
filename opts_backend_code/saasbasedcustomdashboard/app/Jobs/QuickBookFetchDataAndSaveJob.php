<?php

namespace App\Jobs;

use App\Models\ConnectorAccessToken;
use App\Services\Connectors\QuickBooks\QuickBooksReportService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class QuickBookFetchDataAndSaveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    private $time;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private ConnectorAccessToken $connectorAccessToken,
        $year = null
    ) {
        $this->time = $year !== null ? Carbon::now()->setYear($year) : now();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->quickBookReportService()->fetchSaveReportDataForUser($this->connectorAccessToken, $this->time);
    }

    /**
     * Get an instance of the SalesForceAuthService from the service container.
     */
    private function quickBookReportService(): QuickBooksReportService
    {
        return App::make(QuickBooksReportService::class);
    }
}
