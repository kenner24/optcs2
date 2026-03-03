<?php

namespace App\Services;

use App\Enums\UserTypeEnum;
use App\Models\User;

class CompanyService
{
    public function __construct(
        private User $user
    ) {
    }

    /**
     * fetch the single company details
     * @param $companyId
     */
    public function getCompanyDetails($companyId)
    {
        return $this->user->select([
            "id",
            "name",
            "email",
            "type",
            "image",
            "status",
            "company_name",
            "work_email",
            "total_employees",
            "domain_sector",
            "username",
        ])->find($companyId);
    }

    /**
     * Fetch the list of the companies
     */
    public function fetchCompanyList()
    {
        return $this->user->select([
            "id",
            "name",
            "email",
            "type",
            "image",
            "status",
            "company_name",
            "work_email",
            "total_employees",
            "domain_sector",
            "username",
            "email_verified_at"
        ])
        ->role(UserTypeEnum::COMPANY->value)
        ->orderBy('created_at', 'desc');
    }

    /**
     * update the status of the company
     */
    public function updateCompanyStatus($userId, $status)
    {
        return $this->user->where('id', $userId)->update(['status' => $status]);
    }

    public function delete($id)
    {
        return $this->user->where('id', $id)->delete();
    }

    public function updateCompanyDetails($companyId, $payload)
    {
        return $this->user->where('id', $companyId)->update($payload);
    }

    public function createCompanyAccount($payload)
    {
        return $this->user->create($payload)
            ->assignRole(UserTypeEnum::COMPANY->value);
    }

    public function findSocialUser($providerId, $email)
    {
        return $this->user
            ->where('provider_id', $providerId)
            ->where('email', $email)
            ->first();
    }
}
