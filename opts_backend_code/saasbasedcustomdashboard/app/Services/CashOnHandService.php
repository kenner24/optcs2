<?php

namespace App\Services;

use App\Models\QuickBookCashOnHand;
use Illuminate\Support\Facades\DB;

class CashOnHandService
{
    public function __construct(
        private QuickBookCashOnHand $cashOnHand
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->cashOnHand->updateOrCreate(
            [
                'user_id' => $payload['user_id'],
                'month' => $payload['month'],
                'year' => $payload['year'],
            ],
            [
                'amount' => $payload['amount'],
                'currency' => $payload['currency'],
            ]
        );
    }


    public function getDataByYear(
        $year,
        $companyId
    ) {
        return $this->cashOnHand->select(['month', 'year', 'amount', 'currency'])
            ->where([
                "year" => $year,
                "user_id" => $companyId,
            ])
            ->get();
    }
}
