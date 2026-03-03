<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ConnectorEnum;
use App\Http\Controllers\Controller;
use App\Services\Connectors\QuickBooks\QuickBookBudgetService;
use App\Services\Connectors\QuickBooks\QuickBooksReportService;
use Illuminate\Http\Request;

class QuickBooksController extends Controller
{
    public function __construct(
        private QuickBooksReportService $quickBooksReportService,
        private QuickBookBudgetService $quickBookBudgetService,
    ) {
    }


    /**
     * @OA\Get (
     *     path="/api/compute/quick-books-data",
     *     description="Compute the quick books related data for all the user",
     *     tags={"Computation"},
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * =============================================================================
     * Compute the quick books related data for all the user and save it in the database
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchData()
    {
        $result = $this->quickBooksReportService->fetchAndSaveReportDataForAllUsers('year');
        return $this->sendResponse("Computed successfully", $result);
    }

    /**
     * @OA\Get (
     *     path="/api/quickbook-budget-list",
     *     description="Fetch the list of all budget of the user present on quickbooks",
     *     tags={"QuickBook"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * =============================================================================
     * Fetch the list of all budget of the user present on quickbooks
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchBudgetList()
    {
        $connectorList = auth()->user()->connectors;
        $quickBookConnector = $connectorList->firstWhere('connector_type', ConnectorEnum::QUICKBOOKS);
        if ($quickBookConnector !== null) {
            $result = $this->quickBookBudgetService->fetchTheBudgetList($quickBookConnector);
            return $this->sendResponse("Success", $result);
        } else {
            return $this->sendError("Quick books not connected.", HTTP_BAD_REQUEST);
        }
    }
}
