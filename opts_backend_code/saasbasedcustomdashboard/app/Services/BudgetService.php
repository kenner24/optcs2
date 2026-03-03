<?php

namespace App\Services;

use App\Models\QuickBookBudget;
use Illuminate\Support\Facades\DB;

class BudgetService
{
    public function __construct(
        private QuickBookBudget $quickBookBudget
    ) {
    }

    public function updateOrCreate($payload)
    {
        return $this->quickBookBudget->updateOrCreate(
            [
                'user_id' => $payload['user_id'],
                'month' => $payload['month'],
                'year' => $payload['year'],
                'budget_id' => $payload['budget_id'],
            ],
            [
                'amount' => $payload['amount'],
                'currency' => $payload['currency']
            ]
        );
    }

    public function getDataByYear(
        $year,
        $companyId,
        $budgetId
    ) {
        return $this->quickBookBudget->select(['month', 'year', 'amount', 'currency'])
            ->where([
                "year" => $year,
                "user_id" => $companyId,
                "budget_id" => $budgetId
            ])
            ->get();
    }
}
