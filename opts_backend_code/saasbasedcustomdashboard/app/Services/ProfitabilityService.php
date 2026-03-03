<?php

namespace App\Services;

use App\Models\QuickBookProfitability;

class ProfitabilityService
{
    public function __construct(
        private QuickBookProfitability $profitability
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->profitability->updateOrCreate(
            [
                'user_id' => $payload['user_id'],
                'month' => $payload['month'],
                'year' => $payload['year'],
            ],
            [
                'percentage' => $payload['percentage'],
            ]
        );
    }

    public function getDataByYear(
        $year,
        $companyId
    ) {
        return $this->profitability->select(['month', 'year', 'percentage'])
            ->where([
                "year" => $year,
                "user_id" => $companyId,
            ])
            ->get();
    }
}
