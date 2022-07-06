<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'dashboard_access',
            'user_management_access',
            'roles_access',
            'roles_view',
            'roles_create',
            'roles_edit',
            'roles_delete',
            'permissions_access',
            'permissions_view',
            'permissions_create',
            'permissions_edit',
            'permissions_delete',
            'users_access',
            'users_view',
            'users_create',
            'users_edit',
            'users_delete',
        ];

        collect($permissions)->map(function ($p) {
            Permission::firstOrCreate([
                'name' => $p,
                'guard_name' => 'web'
            ]);
        });
    }
}
