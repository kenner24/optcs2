<?php

namespace App\Services;

use App\Enums\ConnectorEnum;
use App\Models\ConnectorPreference;

class ConnectorPreferenceService
{
    public function __construct(
        private ConnectorPreference $connectorPreference
    ) {
    }

    public function updateOrCreate($payload)
    {
        $find = [
            'user_id' => $payload['user_id'],
            'connector_type' => $payload['connector_type'],
            'field_name' => $payload['field_name'],
        ];

        if ($payload['field_name'] === "budget_id") {
            $find['year'] = $payload['year'];
        }

        return $this->connectorPreference->updateOrCreate(
            $find,
            [
                'field_value' => $payload['field_value'],
            ]
        );
    }

    public function getUserBudgetPreference($userId, $year)
    {
        return $this->connectorPreference->where([
            'user_id' => $userId,
            'connector_type' => ConnectorEnum::QUICKBOOKS,
            'field_name' => 'budget_id',
            'year' => $year
        ])->first();
    }

    public function fetchAllPreferences($userId)
    {
        return $this->connectorPreference->where('user_id', $userId)->get();
    }
}
