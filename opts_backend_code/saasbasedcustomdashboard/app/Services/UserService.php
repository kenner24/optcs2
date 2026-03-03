<?php

namespace App\Services;

use App\Enums\UserTypeEnum;
use App\Models\User;

class UserService
{
    public function __construct(
        private User $user
    ) {
    }

    public function findUserByEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function findUserById($userId)
    {
        return $this->user->where('id', $userId)->first();
    }

    /**
     * Find the user with email and reset password token
     * @param string $email
     * @param string $token
     */
    public function findUserResetPassToken($email, $token)
    {
        return $this->user->where('email', $email)->where('reset_pass_token', $token)->first();
    }

    /**
     * update user detail by userId
     * @param int $userId
     */
    public function updateUserDetailsById($userId, $payload)
    {
        return $this->user->where('id', $userId)->update($payload);
    }

    /**
     * get the count of all users except superAdmin
     */
    public function getUsersCount()
    {
        return $this->user->role([UserTypeEnum::COMPANY->value, UserTypeEnum::STAFF->value])->count();
    }

    /**
     * get the count of all users by type
     */
    public function getUsersCountByRole($userRole)
    {
        return $this->user->role($userRole)->count();
    }

    /**
     * Get the count of all staff members of a company
     */
    public function getTotalStaffCount($companyId)
    {
        return $this->user->where('company_id', $companyId)->count();
    }
}
