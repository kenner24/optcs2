<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ReportGoatRequest;
use App\Http\Requests\Api\V1\SaveReportGoalsRequest;
use App\Services\ReportGoalService;
use Illuminate\Http\Request;

class ReportGoalController extends Controller
{

    public function __construct(
        private ReportGoalService $reportGoalService
    ) {
        $this->middleware(['permission:' . PermissionEnum::REPORTS->value]);
    }


    /**
     * @OA\Get (
     *     path="/api/fetch-report-goals",
     *     description="Fetch the goals/targets for the various reports",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *        name="report_name",
     *        in="query",
     *        description="",
     *        required=true,
     *     ),
     *     @OA\Parameter(
     *        name="year",
     *        in="query",
     *        description="",
     *        required=true,
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     *
     * @param \App\Http\Requests\Api\V1\ReportGoatRequest
     * @return \Illuminate\Http\Response
     */
    public function fetchGoals(ReportGoatRequest $request)
    {
        $payload = $request->validated();
        $result = $this->reportGoalService->find(
            $payload['report_name'],
            $payload['year'],
            auth()->user()->id
        );
        return $this->sendResponse('Success', $result);
    }


    /**
     * @OA\Post (
     *     path="/api/save-report-goals",
     *     description="Save the report goals",
     *     tags={"Reports"},
     *     @OA\Response(response="200", description="", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="report_name",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="year",
     *                 type="number",
     *             ),
     *              @OA\Property(
     *                 property="goals",
     *                 type="object",
     *                 example="{Jan:0,Feb:0,Mar:0,Apr:0,May:0,Jun:0,Jul:0,Aug:0,Sep:0,Oct:0,Nov:0,Dec:0}",
     *             ),
     *           ),
     *         ),
     *      ),
     * )
     *
     * */
    public function saveReportGoals(SaveReportGoalsRequest $request)
    {
        $payload = $request->validated();

        foreach ($payload['goals'] as $key => $value) {
            $this->reportGoalService->updateOrCreate([
                'report_name' => $payload['report_name'],
                'year' => $payload['year'],
                'month' => $key,
                'value' => $value,
                'user_id' => auth()->user()->id,
            ]);
        }
        return $this->sendResponse("Goals saved successfully");
    }
}
