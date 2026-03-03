<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Connectors\SalesForce\SalesForceAuthService;
use App\Services\Connectors\SalesForce\SalesForceLeadsService;
use App\Http\Controllers\Controller;
use App\Services\Connectors\SalesForce\SalesForceOpportunityService;
use Illuminate\Http\Request;

class SalesForceController extends Controller
{

    public function __construct(
        private SalesForceAuthService $salesForceAuthService,
        private SalesForceLeadsService $salesForceLeadsService,
        private SalesForceOpportunityService $salesForceOpportunityService,
    ) {
    }

    /**
     * @OA\Get (
     *     path="/api/compute/leads-data",
     *     description="Compute the leads data for all the users",
     *     tags={"Computation"},
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     * Compute the leads data for all users and save it in the database
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchLeadsData()
    {
        $result = $this->salesForceLeadsService->fetchAndSaveLeadsData();
        return $this->sendResponse("Salesforce leads data computed successfully", $result);
    }

    /**
     * @OA\Get (
     *     path="/api/compute/opportunities-data",
     *     description="Compute the opportunities data for all the users",
     *     tags={"Computation"},
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     * Compute the leads data for all users and save it in the database
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchOpportunitiesData()
    {
        $result = $this->salesForceOpportunityService->fetchAndSaveOpportunitiesData();
        return $this->sendResponse("Salesforce opportunities data computed successfully", $result);
    }
}
