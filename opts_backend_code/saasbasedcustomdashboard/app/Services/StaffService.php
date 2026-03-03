<?php

namespace App\Services;

use App\Enums\UserTypeEnum;
use App\Models\User;

class StaffService
{
    public function __construct(
        private User $user
    ) {
    }

    /**
     * Fetch the list of the company staff
     */
    public function fetchStaffList($companyId)
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
        ])->where('company_id', $companyId)->role(UserTypeEnum::STAFF->value);
    }

    /**
     * update the status of the staff member
     */
    public function updateStaffStatus($userId, $status, $companyId = null)
    {
        if (isset($companyId)) {
            return $this->user->where("company_id", $companyId)
                ->where('id', $userId)
                ->update(['status' => $status]);
        }
        return $this->user->where('id', $userId)->update(['status' => $status]);
    }

    /**
     * update the staff details
     */
    public function updateStaffDetails($userId, $companyId, $payload)
    {
        return $this->user->where('company_id', $companyId)
            ->where('id', $userId)
            ->update($payload);
    }

    public function delete($id)
    {
        return $this->user->where('id', $id)->delete();
    }

    /**
     * fetch the single staff details
     * @param $staffId
     * @param $companyId pass the company id if available to fetch the record by matching company id
     */
    public function getStaffDetails($staffId, $companyId = null)
    {
        if ($companyId) {
            return $this->user->with('staffCompanyDetails')
                ->where('company_id', $companyId)
                ->find($staffId);
        }
        return $this->user->with('staffCompanyDetails')->find($staffId);
    }

    /**
     * Get the paginated list of the company staff
     */
    public function paginatedStaffList(
        int $companyId,
        int $offset,
        int $size,
        int $excludeId = null
    ) {
        $query = $this->user->select([
            "id",
            "name",
            "email",
            "image",
            "status",
            "username",
        ])->where('company_id', $companyId);

        if ($excludeId !== null) {
            $query = $query->where("id", "<>", $excludeId);
        }

        return $query->role(UserTypeEnum::STAFF->value)
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($size)
            ->get();
    }

    /**
     * Get the paginated list of the company staff
     */
    public function getStaffCount(
        int $companyId,
        int $excludeId = null
    ) {
        $query = $this->user->where('company_id', $companyId);

        if ($excludeId !== null) {
            $query = $query->where("id", "<>", $excludeId);
        }

        return $query->role(UserTypeEnum::STAFF->value)
            ->count();
    }

    /**
     * create a staff member
     */
    public function create($payload)
    {
        return $this->user->create($payload)->assignRole(UserTypeEnum::STAFF->value);
    }
}
