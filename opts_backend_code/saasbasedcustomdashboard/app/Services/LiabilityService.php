<?php

namespace App\Services;

use App\Models\QuickBookLiability;
use Illuminate\Support\Facades\DB;

class LiabilityService
{
    public function __construct(
        private QuickBookLiability $liability
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->liability->updateOrCreate(
            [
                'user_id' => $payload['user_id'],
                'month' => $payload['month'],
                'year' => $payload['year'],
            ],
            [
                'amount' => $payload['amount'],
                'currency' => $payload['currency']
            ]
        );
    }

    public function getDataByYear(
        $year,
        $companyId
    ) {
        return $this->liability->select(['month', 'year', 'amount', 'currency'])
            ->where([
                "year" => $year,
                "user_id" => $companyId,
            ])
            ->get();
    }
}
