<?php

namespace Database\Seeders;

use App\Enums\UserLevelEnum;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $albaCompany = Company::create([
            'name' => 'PT. Alba Digital Teknologi',
        ]);

        $superAdminRole = Role::create([
            'name' => 'super-admin',
            'guard_name' => 'web',
            'company_id' => $albaCompany->id
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
            'company_id' => null
        ]);
        $adminRole->syncPermissions(Permission::all());

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin'),
            'level' => UserLevelEnum::SUPER_ADMIN,
        ]);

        // $superAdmin->assignRole($superAdminRole);
        DB::table('model_has_roles')->insert([
            'role_id' => $superAdminRole->id,
            'model_type' => get_class($superAdmin),
            'model_id' => $superAdmin->id,
            'company_id' => $albaCompany->id,
        ]);

        $admin = User::create([
            'name' => 'Admin ALBA',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'level' => UserLevelEnum::SUPER_ADMIN,
        ]);
        // $admin->assignRole($adminRole);
        DB::table('model_has_roles')->insert([
            'role_id' => $adminRole->id,
            'model_type' => get_class($admin),
            'model_id' => $admin->id,
            'company_id' => $albaCompany->id,
        ]);
    }
}
