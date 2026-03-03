<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ConnectorEnum;
use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AuthorizationURLRequest;
use App\Http\Requests\Api\V1\ConnectorExchangeTokenRequest;
use App\Jobs\AfterConnectorDataComputedJob;
use App\Jobs\QuickBookFetchDataAndSaveJob;
use App\Services\ConnectorAccessTokenService;
use App\Services\ConnectorPreferenceService;
use App\Services\Connectors\Google\GoogleAuthService;
use App\Services\Connectors\QuickBooks\QuickBooksAuthService;
use App\Services\Connectors\SalesForce\SalesForceAuthService;
use App\Services\Connectors\SalesForce\SalesForceLeadsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConnectorController extends Controller
{
    public function __construct(
        private SalesForceAuthService $salesForceAuthService,
        private SalesForceLeadsService $salesForceLeadsService,
        private QuickBooksAuthService $quickBookAuthService,
        private ConnectorPreferenceService $connectorPreferenceService,
        private ConnectorAccessTokenService $connectorAccessTokenService,
        private GoogleAuthService $googleAuthService,
    ) {
        $this->middleware(['permission:' . PermissionEnum::CONNECTOR->value]);
    }

    /**
     * @OA\Get (
     *     path="/api/connectors/authorization-endpoint",
     *     description="Get the authorization url for the oauth",
     *     tags={"Connectors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *        name="connector_type",
     *        in="query",
     *        description="Allowed values are salesforce, quickbooks, googlesheet",
     *        required=true,
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     * Generate the authorization url for the oauth
     *
     * @param \App\Http\Requests\Api\V1\AuthorizationURLRequest
     * @return \Illuminate\Http\Response
     */
    public function getAuthorizationUrl(AuthorizationURLRequest $request)
    {
        try {
            $payload = $request->validated();
            $url = null;
            switch ($payload['connector_type']) {
                case ConnectorEnum::SALESFORCE->value:
                    $url = $this->salesForceAuthService->generateAuthURL();
                    break;
                case ConnectorEnum::QUICKBOOKS->value:
                    $url = $this->quickBookAuthService->generateAuthURL();
                    break;
                case ConnectorEnum::GOOGLESHEET->value:
                    $url = $this->googleAuthService->generateAuthURL();
                    break;

                default:
                    $url = "";
                    break;
            }

            return $this->sendResponse('Success', ["authorization_url" => $url], HTTP_OK);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post (
     *     path="/api/connectors/add",
     *     description="Add the connector to the application",
     *     tags={"Connectors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="connector_type",
     *                      description="Allowed values salesforce, quickbooks, googlesheet",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="authorization_code",
     *                      description="",
     *                      type="string",
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     * Exchange authorization token and save the access token in database
     *
     * @param \App\Http\Requests\Api\V1\ConnectorExchangeTokenRequest
     * @return \Illuminate\Http\Response
     */
    public function exchangeTokenAndSave(ConnectorExchangeTokenRequest $request)
    {
        try {
            $payload = $request->all();
            $result = null;
            switch ($payload['connector_type']) {
                case ConnectorEnum::SALESFORCE->value:
                    $result = $this
                        ->salesForceAuthService
                        ->fetchAndSaveAccessToken($payload['authorization_code'], $request->user()->id);
                    break;
                case ConnectorEnum::QUICKBOOKS->value:
                    $result = $this
                        ->quickBookAuthService
                        ->fetchAndSaveAccessToken(
                            $payload['authorization_code'],
                            $payload['realmId'],
                            $request->user()->id
                        );
                    break;
                case ConnectorEnum::GOOGLESHEET->value:
                    $result = $this->googleAuthService->fetchAndSaveAccessToken(
                        $payload['authorization_code'],
                        $request->user()->id
                    );
                    break;

                default:
                    break;
            }

            if (isset($result['success']) && $result["success"] === false) {
                return $this->sendError($result['message'], HTTP_BAD_REQUEST);
            }

            return $this->sendResponse('App connected successfully', []);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR, []);
        }
    }

    /**
     * @OA\Post (
     *     path="/api/connectors/add-preference",
     *     description="Save the connector related preference in database",
     *     tags={"Connectors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="connector_type",
     *                      description="Allowed values 'salesforce', 'quickbooks', 'googlesheet'",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="column_name",
     *                      description="Allowed values 'budget_id', 'spreadsheet_id', 'sheet_id'",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="column_value",
     *                      description="",
     *                      type="string",
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     * Save the connector related preference in database
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function saveConnectorPreference(Request $request)
    {
        $payload = $request->validate([
            "connector_type" => [
                "required",
                Rule::in([
                    ConnectorEnum::GOOGLESHEET->value,
                    ConnectorEnum::QUICKBOOKS->value,
                    ConnectorEnum::SALESFORCE->value
                ])
            ],
            "column_name" => [
                "required",
                Rule::in([
                    "budget_id",
                    "spreadsheet_id",
                    "sheet_id"
                ])
            ],
            "column_value" => [
                "required",
            ],
            "year" => [
                "required_if:column_name,budget_id",
                "nullable",
                "numeric",
                "integer",
                "digits:4",
            ]
        ]);

        $this->connectorPreferenceService->updateOrCreate([
            'user_id' => auth()->user()->id,
            'connector_type' => $payload['connector_type'],
            'field_name' => $payload['column_name'],
            'field_value' => $payload['column_value'],
            'year' => $payload['year'] ?? null
        ]);

        if ($payload['connector_type'] ===  ConnectorEnum::QUICKBOOKS->value) {
            $quickBookAccountDetail = $this->connectorAccessTokenService->getUserQuickBookToken(auth()->user()->id);
            QuickBookFetchDataAndSaveJob::withChain([
                new AfterConnectorDataComputedJob(auth()->user()->id, ConnectorEnum::QUICKBOOKS)
            ])->dispatch(
                $quickBookAccountDetail,
                $payload['year']
            );
        }

        return $this->sendResponse("Preference saved successfully");
    }

    /**
     * @OA\Get (
     *     path="/api/connectors/fetch-preferences",
     *     description="Fetch all the saved preferences of the user",
     *     tags={"Connectors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchConnectorPreference()
    {
        $result = $this->connectorPreferenceService->fetchAllPreferences(auth()->user()->id);
        return $this->sendResponse("Success", $result);
    }
}
