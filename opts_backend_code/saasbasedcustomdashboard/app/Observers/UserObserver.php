<?php

namespace App\Observers;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Services\UserService;

class UserObserver
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->type === UserTypeEnum::STAFF) {
            $staffMemberCount = $this->userService->getTotalStaffCount($user->company_id);
            $this->userService->updateUserDetailsById($user->company_id, [
                'total_employees' => $staffMemberCount
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        if ($user->type === UserTypeEnum::STAFF) {
            $staffMemberCount = $this->userService->getTotalStaffCount($user->company_id);
            $this->userService->updateUserDetailsById($user->company_id, [
                'total_employees' => $staffMemberCount
            ]);
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
