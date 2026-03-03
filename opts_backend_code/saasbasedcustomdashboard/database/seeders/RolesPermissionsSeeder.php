<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;

class RolesPermissionsSeeder extends Seeder
{
    private $rolePermissionMap = [
        'super_admin' => [
            'edit_company',
            'delete_company',
            'read_company',
            'create_company',
        ],
        'company' => [
            'edit_staff',
            'delete_staff',
            'read_staff',
            'create_staff',
            "reports",
            "connectors",
            "sub-account"
        ],
        'staff' => []
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createPermission();
        $this->createRole();
    }

    public function createRole()
    {
        foreach ($this->rolePermissionMap as $role => $permission) {
            Role::create(['name' => $role])->givePermissionTo($permission);
        }
    }

    public function createPermission()
    {
        $permissions = array_unique(Arr::flatten(array_values($this->rolePermissionMap)));
        $mappedPermissionArr = Arr::map($permissions, function (string $value, string $key) {
            return ['name' => $value, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()];
        });
        Permission::insert($mappedPermissionArr);
    }
}
