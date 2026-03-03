<?php

namespace App\Services;

use App\Models\SalesForceLead;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function __construct(
        private SalesForceLead $lead
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->lead->updateOrCreate(
            [
                'lead_id' => $payload['lead_id']
            ],
            [
                'user_id' => $payload['user_id'],
                'status' => $payload["status"],
                'source' => $payload["source"],
                'name' => $payload["name"],
                'lead_created_date' => $payload["lead_created_date"],
                'lead_converted_date' => $payload["lead_converted_date"],
                'annual_revenue' => $payload["annual_revenue"] ?? 0,
            ]
        );
    }

    /**
     * Get the leads count by Year of the given companyId
     * @param int $currentYear
     * @param int $companyId
     */
    public function getLeadsCountByYear(
        $year,
        $companyId
    ) {
        return $this->lead->select(
            DB::raw('YEAR(lead_created_date) as year'),
            DB::raw('MONTH(lead_created_date) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('lead_created_date', $year)
            ->where('user_id', $companyId)
            ->groupBy('month')
            ->groupBy('year')
            ->get();
    }

    public function leadsByWeek(
        $year,
        $companyId
    ) {
        return $this->lead->select(DB::raw('WEEK(created_at) as week, COUNT(*) as total'))
            ->whereYear('lead_created_date', $year)
            ->where('user_id', $companyId)
            ->groupBy('week')
            ->orderBy('week')
            ->get();
    }
}
