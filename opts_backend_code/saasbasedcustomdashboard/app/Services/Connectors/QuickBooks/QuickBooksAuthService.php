<?php

namespace App\Services\Connectors\QuickBooks;

use App\Enums\ConnectorEnum;
use App\Jobs\QuickBookFetchDataAndSaveJob;
use App\Services\ConnectorAccessTokenService;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\DataService\DataService;

class QuickBooksAuthService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $oauthScope;
    private $dataService;
    private $baseUrl;

    public function __construct(
        private ConnectorAccessTokenService $connectorAccessTokenService
    ) {
        $this->clientId = config('quickBooks.client_id');
        $this->clientSecret = config('quickBooks.client_secret');
        $this->oauthScope = config('quickBooks.oauth_scope');
        $this->redirectUri = config('quickBooks.oauth_redirect_uri');
        $this->baseUrl = config('quickBooks.base_url');
        $this->dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $this->clientId,
            'ClientSecret' =>  $this->clientSecret,
            'RedirectURI' => $this->redirectUri,
            'scope' => $this->oauthScope,
            'baseUrl' => $this->baseUrl
        ]);
    }



    /**
     * Generate a authorization url for the quickBooks oauth authentication
     * @return string
     */
    public function generateAuthURL()
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        return $OAuth2LoginHelper->getAuthorizationCodeURL();
    }

    /**
     * Fetch and save the access token into the database after the successful authorization
     * @param string $code
     * @param int $userId
     * @return Array
     */
    public function fetchAndSaveAccessToken(
        $code,
        $realmId,
        $userId,
    ) {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $result = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken(
            $code,
            $realmId
        );
        
        $result = $this->connectorAccessTokenService->updateOrCreate([
            "user_id" => $userId,
            "connector_type" => ConnectorEnum::QUICKBOOKS,
            "access_token" => $result->getAccessToken(),
            "refresh_token" => $result->getRefreshToken(),
            "issued_at" => null,
            "quick_book_realmId" => $realmId,
            "access_token_expired_at" => $result->getAccessTokenExpiresAt(),
            "refresh_token_expired_at" => $result->getRefreshTokenExpiresAt(),
        ]);

        QuickBookFetchDataAndSaveJob::dispatch($result);
        
        return [
            "success" => true,
            "message" => "Success"
        ];
    }
}
