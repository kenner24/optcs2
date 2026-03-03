<?php

namespace App\Observers;

use App\Models\ConnectorAccessToken;
use App\Services\UserService;

class ConnectorAccessTokenObserver
{
    public function __construct(
        private UserService $userService,
    ) {
    }
    /**
     * Handle the ConnectorAccessToken "created" event.
     */
    public function created(ConnectorAccessToken $connectorAccessToken): void
    {
        $this->userService->updateUserDetailsById($connectorAccessToken->user_id, [
            'quick_book_realmId' => $connectorAccessToken->quick_book_realmId ?? null
        ]);
    }

    /**
     * Handle the ConnectorAccessToken "updated" event.
     */
    public function updated(ConnectorAccessToken $connectorAccessToken): void
    {
        $this->userService->updateUserDetailsById($connectorAccessToken->user_id, [
            'quick_book_realmId' => $connectorAccessToken->quick_book_realmId ?? null
        ]);
    }

    /**
     * Handle the ConnectorAccessToken "deleted" event.
     */
    public function deleted(ConnectorAccessToken $connectorAccessToken): void
    {
        //
    }

    /**
     * Handle the ConnectorAccessToken "restored" event.
     */
    public function restored(ConnectorAccessToken $connectorAccessToken): void
    {
        //
    }

    /**
     * Handle the ConnectorAccessToken "force deleted" event.
     */
    public function forceDeleted(ConnectorAccessToken $connectorAccessToken): void
    {
        //
    }
}
