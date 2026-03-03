<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ConnectorEnum;
use App\Http\Controllers\Controller;
use App\Services\Connectors\Google\GoogleSheetService;
use Illuminate\Http\Request;

class GoogleSheetController extends Controller
{
    public function __construct(
        private GoogleSheetService $googleSheetService,
    ) {
    }


    /**
     * @OA\Get (
     *     path="/api/google-spreadsheet-list",
     *     description="Fetch the list of all sheets present on user google account",
     *     tags={"GoogleSheets"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * =============================================================================
     * Fetch the list of all sheets present on user google account
     *
     * @return \Illuminate\Http\Response
     */
    public function getSpreadSheetList()
    {
        $authenticatedUser = auth()->user();
        $connectorList = $authenticatedUser->connectors;

        $googleSheetConnector = $connectorList->firstWhere('connector_type', ConnectorEnum::GOOGLESHEET);
        if ($googleSheetConnector !== null) {
            $result = $this->googleSheetService->getSpreadSheetList($googleSheetConnector, $authenticatedUser->id);
            return $this->sendResponse("List fetch successfully", $result);
        } else {
            return $this->sendError("Google sheet not connected.", HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get (
     *     path="/api/fetch-spreadsheet-sheets-list/{id}",
     *     description="Fetch the sheets present within the spreadsheet",
     *     tags={"GoogleSheets"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         description="Spread sheet Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * =============================================================================
     * Fetch the list of the sheets present in the spread sheet.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchSpreadSheetSheetList($spreadSheetId)
    {
        $authenticatedUser = auth()->user();
        $connectorList = $authenticatedUser->connectors;

        $googleSheetConnector = $connectorList->firstWhere('connector_type', ConnectorEnum::GOOGLESHEET);
        if ($googleSheetConnector !== null) {
            $result = $this->googleSheetService->fetchSheetList(
                $googleSheetConnector,
                $authenticatedUser->id,
                $spreadSheetId
            );
            return $this->sendResponse("Success", $result);
        } else {
            return $this->sendError("Google sheet not connected.", HTTP_BAD_REQUEST);
        }
    }


    /**
     * @OA\Get (
     *     path="/api/fetch-sheet-headers/{id}",
     *     description="",
     *     tags={"GoogleSheets"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         description="Spreadsheet Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *        name="sheet_name",
     *        in="query",
     *        description="Enter the selected sheet name",
     *        required=true,
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * =============================================================================
     * Fetch the sheet column list
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchSpreadSheetHeaders(Request $request, $spreadSheetId)
    {
        $payload = $request->validate([
            'sheet_name' => 'required|string'
        ]);

        $authenticatedUser = auth()->user();
        $connectorList = $authenticatedUser->connectors;

        $googleSheetConnector = $connectorList->firstWhere('connector_type', ConnectorEnum::GOOGLESHEET);
        if ($googleSheetConnector !== null) {
            try {
                $result = $this->googleSheetService->fetchColumnHeadings(
                    $googleSheetConnector,
                    $authenticatedUser->id,
                    $spreadSheetId,
                    $payload['sheet_name']
                );
                return $this->sendResponse("Success", $result);
            } catch (\Exception $th) {
                return $this->sendError("Unable to read the columns for the given sheet", HTTP_BAD_REQUEST);
            }
        } else {
            return $this->sendError("Google sheet not connected.", HTTP_BAD_REQUEST);
        }
    }
}
