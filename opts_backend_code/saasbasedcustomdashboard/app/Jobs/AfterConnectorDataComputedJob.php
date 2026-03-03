<?php

namespace App\Jobs;

use App\Notifications\ConnectorDataProcessingCompletedNotification;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class AfterConnectorDataComputedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 5;

    private $userId;
    private $connectorType;


    /**
     * Create a new job instance.
     */
    public function __construct($userId, $connectorType)
    {
        $this->userId = $userId;
        $this->connectorType = $connectorType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $userDetails = $this->userService()->findUserById($this->userId);
        $userDetails->notify(new ConnectorDataProcessingCompletedNotification($this->connectorType));
    }

    /**
     * Get an instance of the SalesForceAuthService from the service container.
     */
    private function userService(): UserService
    {
        return App::make(UserService::class);
    }
}
