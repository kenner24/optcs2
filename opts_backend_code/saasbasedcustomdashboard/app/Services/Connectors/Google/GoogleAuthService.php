<?php

namespace App\Services\Connectors\Google;

use App\Enums\ConnectorEnum;
use App\Services\ConnectorAccessTokenService;
use Google\Client;
use Illuminate\Support\Str;

class GoogleAuthService
{
    private $clientId;
    private $clientSecret;
    private $redirectURL;
    private $googleClient;

    public function __construct(
        private ConnectorAccessTokenService $connectorAccessTokenService
    ) {
        $this->clientId = config('googleSheets.client_id');
        $this->clientSecret = config('googleSheets.client_secret');
        $this->redirectURL = config('googleSheets.redirect_uri');
        $this->googleClient = new Client();
        $this->googleClient->setClientId($this->clientId);
        $this->googleClient->setClientSecret($this->clientSecret);
        $this->googleClient->setRedirectUri($this->redirectURL);
    }

    /**
     * Generate a oauth url to get the authorization for the google sheets
     * @return string
     */
    public function generateAuthURL()
    {
        $this->googleClient->addScope([
            'https://www.googleapis.com/auth/spreadsheets.readonly',
            'https://www.googleapis.com/auth/drive.readonly'
        ]);
        $this->googleClient->setAccessType('offline');
        $this->googleClient->setState(Str::random(10));
        $this->googleClient->setIncludeGrantedScopes(true);
        return $this->googleClient->createAuthUrl();
    }

    /**
     * Exchange the authorization code and save the access token in database
     * @param string $code
     * @param int $userId
     */
    public function fetchAndSaveAccessToken($code, $userId)
    {
        $result = $this->googleClient->fetchAccessTokenWithAuthCode($code);
        $this->connectorAccessTokenService->updateOrCreate([
            "user_id" => $userId,
            "connector_type" => ConnectorEnum::GOOGLESHEET,
            "access_token" => $result['access_token'],
            "refresh_token" => $result['refresh_token'],
            "issued_at" => $result['created'],
            "access_token_expired_at" => $result['expires_in']
        ]);

        return [
            "success" => true,
            "message" => "Success"
        ];
    }
}
