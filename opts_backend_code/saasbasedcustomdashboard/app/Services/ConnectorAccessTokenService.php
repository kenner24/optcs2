<?php

namespace App\Services;

use App\Enums\ConnectorEnum;
use App\Models\ConnectorAccessToken;
use Carbon\Carbon;

class ConnectorAccessTokenService
{
    public function __construct(
        private ConnectorAccessToken $connectorAccessToken
    ) {
    }

    /**
     * update or create a record in the database
     */
    public function updateOrCreate($payload)
    {
        $data = [
            "access_token" => $payload["access_token"],
            "refresh_token" => $payload["refresh_token"],
        ];

        if ($payload['connector_type'] === ConnectorEnum::SALESFORCE) {
            $payload["issued_at"] = $payload["issued_at"] !== null ?
                Carbon::parse((int)$payload["issued_at"] / 1000) :
                null;
        }

        if ($payload['connector_type'] === ConnectorEnum::GOOGLESHEET) {
            $payload["issued_at"] = Carbon::parse($payload["issued_at"]);
            $data["access_token_expired_at"] = now()
                ->addSeconds($payload["access_token_expired_at"])
                ->subMinutes(2) ?? null;
        }

        if ($payload['connector_type'] === ConnectorEnum::QUICKBOOKS) {
            if (isset($payload["quick_book_realmId"])) {
                $data["quick_book_realmId"] = $payload["quick_book_realmId"];
            }

            $data["access_token_expired_at"] = Carbon::parse($payload["access_token_expired_at"]) ?? null;
            $data["refresh_token_expired_at"] = Carbon::parse($payload["refresh_token_expired_at"]) ?? null;
        }

        return $this->connectorAccessToken->updateOrCreate(
            [
                "user_id" => $payload['user_id'],
                "connector_type" => $payload['connector_type']
            ],
            $data
        );
    }

    /**
     * Get all the user's details for the salesforce connector type
     */
    public function getAllUserSalesForceToken()
    {
        return $this->connectorAccessToken->where('connector_type', ConnectorEnum::SALESFORCE)->get();
    }

    /**
     * Get all the user's details for the quickbooks connector type
     */
    public function getAllUserQuickBookTokens()
    {
        return $this->connectorAccessToken->where('connector_type', ConnectorEnum::QUICKBOOKS)->get();
    }

    /**
     * Get all the user's details for the google sheet connector type
     */
    public function getAllUserGoogleSheetTokens()
    {
        return $this->connectorAccessToken->where('connector_type', ConnectorEnum::GOOGLESHEET)->get();
    }

    /**
     * Get the user quick book account details
     */
    public function getUserQuickBookToken($userId)
    {
        return $this->connectorAccessToken->where([
            'connector_type' => ConnectorEnum::QUICKBOOKS,
            'user_id' => $userId
        ])->first();
    }

    /**
     * Get the user google access token
     */
    public function getUserGoogleSheetToken($userId)
    {
        return $this->connectorAccessToken->where([
            'connector_type' => ConnectorEnum::GOOGLESHEET,
            'user_id' => $userId
        ])->first();
    }
}
