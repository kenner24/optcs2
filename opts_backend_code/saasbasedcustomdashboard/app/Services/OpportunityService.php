<?php

namespace App\Services;

use App\Enums\SalesForceStagesEnum;
use App\Models\SalesForceOpportunity;
use Illuminate\Support\Facades\DB;

class OpportunityService
{
    public function __construct(
        private SalesForceOpportunity $opportunity
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->opportunity->updateOrCreate(
            [
                'opportunity_id' => $payload['opportunity_id']
            ],
            [
                'user_id' => $payload['user_id'],
                'opportunity_id' => $payload["opportunity_id"],
                'name' => $payload["name"],
                'source' => $payload["source"],
                'stage' => $payload["stage"],
                'opportunity_created_date' => $payload["opportunity_created_date"],
                'opportunity_close_date' => $payload["opportunity_close_date"],
                'amount' => $payload["amount"] ?? 0,
                'expected_revenue' => $payload["expected_revenue"] ?? 0,
            ]
        );
    }


    /**
     * Get the opportunity count by Year of the given companyId
     * @param int $currentYear
     * @param int $companyId
     */
    public function getOpportunityCountByYear(
        $year,
        $companyId
    ) {
        return $this->opportunity->select(
            DB::raw('YEAR(opportunity_created_date) as year'),
            DB::raw('MONTH(opportunity_created_date) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('opportunity_created_date', $year)
            ->where('user_id', $companyId)
            ->groupBy('month')
            ->groupBy('year')
            ->get();
    }


    public function opportunityByStage(
        $year,
        $companyId
    ) {
        return $this->opportunity->select(
            'stage',
            DB::raw('COUNT(*) as count')
        )->whereYear('opportunity_created_date', $year)
            ->where('user_id', $companyId)
            ->groupBy('stage')
            ->get();
    }

    public function opportunityClosedPerMonthWonLost(
        $year,
        $companyId
    ) {
        return $this->opportunity->select(
            'stage',
            DB::raw('DATE_FORMAT(opportunity_close_date, "%b") as month'),
            DB::raw('YEAR(opportunity_close_date) as year'),
            DB::raw('COUNT(*) as count'),
        )
            ->whereYear('opportunity_close_date', $year)
            ->where('user_id', $companyId)
            ->whereIn('stage', [
                SalesForceStagesEnum::CLOSED_WON->value,
                SalesForceStagesEnum::CLOSED_LOST->value
            ])
            ->groupBy('month')
            ->groupBy('year')
            ->groupBy('stage')
            ->get();
    }

    public function top10OpenOpportunities(
        $year,
        $companyId
    ) {
        return $this->opportunity->whereNotIn('stage', [
            SalesForceStagesEnum::CLOSED_WON->value,
            SalesForceStagesEnum::CLOSED_LOST->value
        ])
            ->whereYear('opportunity_created_date', $year)
            ->where('user_id', $companyId)
            ->orderBy('amount', 'desc')
            ->limit(2)
            ->get();
    }

    public function last10OpenOpportunities(
        $year,
        $companyId
    ) {
        return $this->opportunity->whereNotIn('stage', [
            SalesForceStagesEnum::CLOSED_WON->value,
            SalesForceStagesEnum::CLOSED_LOST->value
        ])
            ->whereYear('opportunity_created_date', $year)
            ->where('user_id', $companyId)
            ->orderBy('amount', 'asc')
            ->limit(2)
            ->get();
    }
}
