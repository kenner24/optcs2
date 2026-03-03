<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\CompanyReportService;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class CompanyReportController extends Controller
{
    public function __construct(
        private CompanyService $companyService,
        private CompanyReportService $companyReportService
    ) {
    }

    /**
     * Display the Company report page view
     */
    public function showView()
    {
        $companyId = Crypt::decrypt(request()->get('id'));
        $details = $this->companyService->getCompanyDetails($companyId);
        return view('superAdmin.company.reports.index', ['companyDetails' => $details]);
    }

    /**
     * fetch the data for the different type of reports
     */
    public function fetchChartsData(Request $request)
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
            'company_id' => ['bail', 'required', 'string']
        ]);

        $companyId = Crypt::decrypt($payload['company_id']);

        switch ($payload['report_type']) {
            case 'leads-generated':
                $result = $this->companyReportService->getLeadsGeneratedChartData($companyId);
                break;

            case 'opportunities':
                $result = $this->companyReportService->getOpportunityChartData($companyId);
                break;

            case 'current-liabilities':
                $result = $this->companyReportService->getLiabilitiesChartData($companyId);
                break;

            case 'cash-on-hand':
                $result = $this->companyReportService->getCashOnHandChartData($companyId);
                break;

            case 'monthly-expenses':
                $result = $this->companyReportService->getMonthlyExpenseChartData($companyId);
                break;

            case 'profitability-percentage':
                $result = $this->companyReportService->getProfitabilityPercentageChartData($companyId);
                break;
        }

        return $this->sendResponse('Data fetched successfully', $result);
    }
}
