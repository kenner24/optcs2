<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Services\CompanyReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportsController extends Controller
{
    public function __construct(
        private CompanyReportService $companyReportService
    ) {
        $this->middleware(['permission:' . PermissionEnum::REPORTS->value]);
    }

    /**
     * @OA\Get (
     *     path="/api/reports",
     *     description="Get the data for the charts in reports of the company dashboard",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *        name="report_type",
     *        in="query",
     *        description="Allowed values are 'leads-generated', 'opportunities', 'current-liabilities', 'cash-on-hand', 'monthly-expenses', 'profitability-percentage' ",
     *        required=true,
     *     ),
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     * ========================================================
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $reportTypes = [
            'leads-generated',
            'opportunities',
            'current-liabilities',
            'cash-on-hand',
            'monthly-expenses',
            'profitability-percentage'
        ];
        $result = [];
        $payload = $request->validate([
            'report_type' => ['bail', 'required', 'string', Rule::in($reportTypes)],
            'year' => [
                "required",
                "numeric",
                "integer",
                "digits:4",
            ]
        ]);

        $companyId = auth()->user()->id;

        switch ($payload['report_type']) {
            case 'leads-generated':
                $result = $this->companyReportService->getLeadsGeneratedChartData($companyId, $payload['year']);
                break;

            case 'opportunities':
                $result = $this->companyReportService->getOpportunityChartData($companyId, $payload['year']);
                break;

            case 'current-liabilities':
                $result = $this->companyReportService->getLiabilitiesChartData($companyId, $payload['year']);
                break;

            case 'cash-on-hand':
                $result = $this->companyReportService->getCashOnHandChartData($companyId, $payload['year']);
                break;

            case 'monthly-expenses':
                $result = $this->companyReportService->getMonthlyExpenseChartData($companyId, $payload['year']);
                break;

            case 'profitability-percentage':
                $result = $this->companyReportService->getProfitabilityPercentageChartData(
                    $companyId,
                    $payload['year']
                );
                break;
        }

        return $this->sendResponse('Data fetched successfully', $result);
    }
}
