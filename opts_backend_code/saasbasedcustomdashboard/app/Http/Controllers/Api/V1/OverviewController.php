<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\MonthEnum;
use App\Enums\SalesForceStagesEnum;
use App\Http\Controllers\Controller;
use App\Services\LeadService;
use App\Services\OpportunityService;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function __construct(
        private LeadService $leadService,
        private OpportunityService $opportunityService
    ) {
    }

    /**
     * @OA\Get (
     *     path="/api/overview",
     *     description="",
     *     tags={"Overview"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *        name="year",
     *        in="query",
     *        description="",
     *        required=true,
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * =============================================================================
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $payload = $request->validate([
            "year" => [
                "required",
                "numeric",
                "integer",
                "digits:4",
            ],
        ]);

        $leadsByWeek = $this->leadService->leadsByWeek(
            $payload['year'],
            $user->id
        );

        $opportunityByStage = $this->opportunityService->opportunityByStage(
            $payload['year'],
            $user->id
        );

        $tempOpportunityClosedPerMonth = $this->opportunityService->opportunityClosedPerMonthWonLost(
            $payload['year'],
            $user->id
        );

        $opportunityClosedPerMonthLost = [];
        $opportunityClosedPerMonthWon = [];

        foreach (MonthEnum::cases() as $monthIndex => $monthValue) {
            $temp = $tempOpportunityClosedPerMonth->firstWhere('month', '=', $monthValue->value);

            if ($temp?->stage == SalesForceStagesEnum::CLOSED_LOST->value) {
                array_push($opportunityClosedPerMonthWon, [
                    "month" => $monthValue->value,
                    "year" => $payload['year'],
                    "count" => 0,
                    "stage" => SalesForceStagesEnum::CLOSED_WON->value
                ]);
                array_push($opportunityClosedPerMonthLost, [
                    "month" => $monthValue->value,
                    "year" => $payload['year'],
                    "count" => $temp?->count,
                    "stage" => SalesForceStagesEnum::CLOSED_LOST->value
                ]);
            } elseif ($temp?->stage == SalesForceStagesEnum::CLOSED_WON->value) {
                array_push($opportunityClosedPerMonthWon, [
                    "month" => $monthValue->value,
                    "year" => $payload['year'],
                    "count" => $temp?->count,
                    "stage" => SalesForceStagesEnum::CLOSED_WON->value
                ]);
                array_push($opportunityClosedPerMonthLost, [
                    "month" => $monthValue->value,
                    "year" => $payload['year'],
                    "count" => 0,
                    "stage" => SalesForceStagesEnum::CLOSED_LOST->value
                ]);
            } else {
                array_push($opportunityClosedPerMonthWon, [
                    "month" => $monthValue->value,
                    "year" => $payload['year'],
                    "count" => 0,
                    "stage" => SalesForceStagesEnum::CLOSED_WON->value
                ]);
                array_push($opportunityClosedPerMonthLost, [
                    "month" => $monthValue->value,
                    "year" => $payload['year'],
                    "count" => 0,
                    "stage" => SalesForceStagesEnum::CLOSED_LOST->value
                ]);
            }
        }

        $top10OpenOpportunity = $this->opportunityService->top10OpenOpportunities(
            $payload['year'],
            $user->id
        );

        $last10OpenOpportunity = $this->opportunityService->last10OpenOpportunities(
            $payload['year'],
            $user->id
        );

        return $this->sendResponse('Data fetched successfully', [
            'leads_per_week' => $leadsByWeek,
            'opportunity_by_stage' => $opportunityByStage,
            'opportunity_closed_per_month_won_lost' => [
                'won' => $opportunityClosedPerMonthWon,
                'lost' => $opportunityClosedPerMonthLost
            ],
            'top_10_opportunity' => $top10OpenOpportunity,
            'last_10_opportunity' => $last10OpenOpportunity,
        ]);
    }
}
