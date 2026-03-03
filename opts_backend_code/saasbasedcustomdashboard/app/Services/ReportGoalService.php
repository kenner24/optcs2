<?php

namespace App\Services;

use App\Models\ReportGoal;

class ReportGoalService
{
    public function __construct(
        private ReportGoal $reportGoal
    ) {
    }

    public function find(
        $reportName,
        $year,
        $companyId
    ) {
        return $this->reportGoal->where([
            'report_name' => $reportName,
            'year' => $year,
            'user_id' => $companyId
        ])->get();
    }


    public function updateOrCreate(
        $payload
    ) {
        return $this->reportGoal->updateOrCreate(
            [
                'user_id' => $payload['user_id'],
                'month' => $payload['month'],
                'year' => $payload['year'],
                'report_name' => $payload['report_name'],
            ],
            [
                'value' => $payload['value'],
            ]
        );
    }

    public function findReportGoals(
        $year,
        $reportName,
        $userId
    ) {
        return $this->reportGoal->select('year', 'month', 'value')->where([
            'report_name' => $reportName,
            'year' => $year,
            'user_id' => $userId
        ])->get();
    }
}
