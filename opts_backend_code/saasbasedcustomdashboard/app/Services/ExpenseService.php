<?php

namespace App\Services;

use App\Models\QuickBookExpense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(
        private QuickBookExpense $expenses
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->expenses->updateOrCreate(
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
        return $this->expenses->select(['month', 'year', 'amount', 'currency'])
            ->where([
                "year" => $year,
                "user_id" => $companyId,
            ])
            ->get();
    }
}
