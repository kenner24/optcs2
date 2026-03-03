<?php

namespace App\Services\Connectors\SalesForce;

use App\Enums\ConnectorEnum;
use App\Jobs\AfterConnectorDataComputedJob;
use App\Jobs\SalesForceFetchDataJob;
use App\Services\ConnectorAccessTokenService;
use Illuminate\Support\Facades\Http;

class SalesForceAuthService
{
    private $loginAPIBaseURL;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $apiBaseURL;

    public function __construct(
        private ConnectorAccessTokenService $connectorAccessTokenService
    ) {
        $this->loginAPIBaseURL = config('salesforce.login_api_base_url');
        $this->apiBaseURL = config('salesforce.api_base_url');
        $this->redirectUri = config('salesforce.redirect_uri');
        $this->clientId = config('salesforce.client_id');
        $this->clientSecret = config('salesforce.client_secret');
    }

    private function getAccessToken($code, $grantType)
    {
        $payload = [
            'grant_type' => $grantType,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        if ($grantType === 'authorization_code') {
            $payload['code'] = $code;
            $payload['redirect_uri'] = $this->redirectUri;
        }

        if ($grantType === 'refresh_token') {
            $payload['refresh_token'] = $code;
        }

        $result = Http::asForm()->post("{$this->loginAPIBaseURL}services/oauth2/token", $payload);

        $result = $result->json();

        if (isset($result['error'])) {
            return [
                "success" => false,
                "message" => "Not able to connect the application try again"
            ];
        }

        return [
            "success" => true,
            "message" => "Success",
            "data" => $result
        ];
    }

    /**
     * Fetch and save the access token into the database after the successful authorization
     * @param string $code
     * @param int $userId
     * @return Array
     */
    public function fetchAndSaveAccessToken($code, $userId)
    {
        $result = $this->getAccessToken($code, 'authorization_code');

        if ($result["success"] === false) {
            return $result;
        }

        $result = $this->connectorAccessTokenService->updateOrCreate([
            "user_id" => $userId,
            "connector_type" => ConnectorEnum::SALESFORCE,
            "access_token" => $result["data"]["access_token"],
            "refresh_token" => $result["data"]["refresh_token"],
            "issued_at" => $result["data"]["issued_at"],
        ]);

        SalesForceFetchDataJob::withChain([
            new AfterConnectorDataComputedJob($userId, ConnectorEnum::SALESFORCE)
        ])->dispatch($result);
        
        return [
            "success" => true,
            "message" => "Success"
        ];
    }


    /**
     * Refresh the token and save it in the database
     * @param string $token
     * @param int $userId
     * @return Array
     */
    public function refreshToken($token, $userId)
    {
        $result = $this->getAccessToken($token, 'refresh_token');

        if ($result["success"] === false) {
            return $result;
        }

        $this->connectorAccessTokenService->updateOrCreate([
            "user_id" => $userId,
            "access_token" => $result["data"]["access_token"],
            "refresh_token" => $token,
            "issued_at" => $result["data"]["issued_at"],
            "connector_type" => ConnectorEnum::SALESFORCE
        ]);

        return [
            "success" => true,
            "message" => "Success",
            "token" => $result["data"]["access_token"]
        ];
    }

    /**
     * Generate a authorization url for the salesforce oauth authentication
     * @return string
     */
    public function generateAuthURL()
    {
        $redirectUri = urlencode($this->redirectUri);
        return "{$this->loginAPIBaseURL}services/oauth2/authorize?client_id={$this->clientId}&redirect_uri={$redirectUri}&response_type=code";
    }

    /**
     * This function will do the introspection of the given token 
     * and return boolean value
     * @param string $token
     * @return boolean
     */
    public function tokenIntrospection($token)
    {
        $result = HTTP::asForm()->post("{$this->apiBaseURL}/services/oauth2/introspect", [
            "token" => $token,
            "token_type_hint" => 'access_token',
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret
        ])->json();

        return $result['active'];
    }
}
