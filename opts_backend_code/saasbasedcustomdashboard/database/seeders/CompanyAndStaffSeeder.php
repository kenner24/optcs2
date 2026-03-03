<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanyAndStaffSeeder extends Seeder
{
    private $companyNumber = 100;
    private $staffNumber = 15;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyIds = [];

        for ($i = 0; $i < $this->companyNumber; $i++) {
            $company = User::create([
                'name'       => fake()->name(),
                'email'      => fake()->unique()->safeEmail(),
                'password'   => Hash::make(fake()->password()),
                'type'       => UserTypeEnum::COMPANY,
                'status'          => UserStatusEnum::ACTIVE,
                'company_name'    => fake()->company(),
                'work_email'      => fake()->companyEmail(),
                'total_employees' => fake()->randomNumber(3, false),
                'domain_sector'   => fake()->word(),
                'slug'            => fake()->word(),
                'email_verified_at' => now(),
                'username'        => fake()->userName(),
                'image' => fake()->imageUrl(50, 50, 'cats', true)
            ]);
            array_push($companyIds, $company->id);
            $company->assignRole(UserTypeEnum::COMPANY->value);
            $company->givePermissionTo([
                PermissionEnum::CONNECTOR->value,
                PermissionEnum::REPORTS->value,
                PermissionEnum::SUB_ACCOUNT->value
            ]);
        }

        foreach ($companyIds as $key => $value) {
            for ($i = 0; $i < $this->staffNumber; $i++) {
                $staff = User::create(
                    [
                        'name'       => fake()->name(),
                        'email'      => fake()->unique()->safeEmail(),
                        'password'   => Hash::make(fake()->password()),
                        'type'       => UserTypeEnum::STAFF,
                        'status'          => UserStatusEnum::ACTIVE,
                        'company_name'    => null,
                        'work_email'      => null,
                        'total_employees' => null,
                        'domain_sector'   => null,
                        'slug'            => null,
                        'email_verified_at' => now(),
                        'username'        => fake()->userName(),
                        'image' => fake()->imageUrl(50, 50, 'cats', true),
                        'company_id' => $value
                    ]
                );
                $staff->assignRole(UserTypeEnum::STAFF->value);
            }
        }
    }
}
