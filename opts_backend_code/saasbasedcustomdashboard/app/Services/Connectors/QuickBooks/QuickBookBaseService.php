<?php

namespace App\Services\Connectors\QuickBooks;

use App\Enums\ConnectorEnum;
use App\Services\ConnectorAccessTokenService;
use Illuminate\Support\Facades\App;
use QuickBooksOnline\API\DataService\DataService;

class QuickBookBaseService
{

    private $apiBaseUrl;
    private $minorVersion;
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $oauthHelper;
    private $dataService;
    private $connectorAccessTokenService;

    public function __construct()
    {
        $this->apiBaseUrl = config('quickBooks.api_base_url');
        $this->minorVersion = config('quickBooks.minor_version');
        $this->clientId = config('quickBooks.client_id');
        $this->clientSecret = config('quickBooks.client_secret');
        $this->baseUrl = config('quickBooks.base_url');
        $this->connectorAccessTokenService = App::make(ConnectorAccessTokenService::class);
    }

    public function getMinorVersion()
    {
        return $this->minorVersion;
    }

    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    /**
     * Set the realmId, refresh token, for the quick book data service
     * of a company
     * @param string refreshToken
     * @param string realmId
     */
    private function setCompanyConfiguration(
        $refreshToken,
        $realmId,
    ) {
        $this->dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->clientId,
            'ClientSecret' => $this->clientSecret,
            'refreshTokenKey' => $refreshToken,
            'QBORealmId' => $realmId,
            'baseUrl' => $this->baseUrl
        ));
        $this->oauthHelper = $this->dataService->getOAuth2LoginHelper();
    }

    /**
     * Refresh the company access token
     * @param number $userId
     * @param string $refreshToken
     * @param string $realmId
     */
    public function refreshToken(
        $userId,
        $refreshToken,
        $realmId
    ) {
        $this->setCompanyConfiguration(
            $refreshToken,
            $realmId,
        );
        $accessTokenObj = $this->oauthHelper->refreshAccessTokenWithRefreshToken($refreshToken);
        $this->dataService->updateOAuth2Token($accessTokenObj);
        // set the new refresh and access token in database
        return $this->connectorAccessTokenService->updateOrCreate([
            "user_id" => $userId,
            "connector_type" => ConnectorEnum::QUICKBOOKS,
            "access_token" => $accessTokenObj->getAccessToken(),
            "refresh_token" => $accessTokenObj->getRefreshToken(),
            "issued_at" => null,
            "access_token_expired_at" => $accessTokenObj->getAccessTokenExpiresAt(),
            "refresh_token_expired_at" => $accessTokenObj->getRefreshTokenExpiresAt(),
        ]);
    }
}
