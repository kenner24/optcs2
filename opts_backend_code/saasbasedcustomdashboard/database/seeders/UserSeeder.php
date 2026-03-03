<?php

namespace Database\Seeders;

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'       => 'Super Admin',
            'email'      => 'superAdmin@example.com',
            'password'   => Hash::make(Str::random(40)),
            'type'       => UserTypeEnum::SUPER_ADMIN,
            'status'          => UserStatusEnum::ACTIVE,
            'company_name'    => null,
            'work_email'      => null,
            'total_employees' => null,
            'domain_sector'   => null,
            'slug'            => null,
            'username'        => "superAdmin",
            'email_verified_at' => now(),
        ]);
        $user->assignRole(UserTypeEnum::SUPER_ADMIN->value);
    }
}
