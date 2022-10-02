<?php

namespace Database\Seeders;

use App\Enums\UserLevelEnum;
use App\Models\Company;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::whereNull('company_id')->where('name', 'admin')->where('guard_name', 'web')->first();
        $admin1 = User::create([
            'name' => 'Admin PT. Satu',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('12345678'),
            'level' => UserLevelEnum::ADMIN,
        ]);

        $company1 = Company::create([
            'name' => 'PT. Satu',
        ]);

        $tenant1 = Tenant::create([
            'company_id' => $company1->id,
            'name' => 'Tenant 1 PT Satu',
        ]);

        $tenant2 = Tenant::create([
            'company_id' => $company1->id,
            'name' => 'Tenant 2 PT Satu',
        ]);

        $admin1->companies()->sync([$company1->id]);
        $admin1->tenants()->sync([$tenant1->id, $tenant2->id]);

        DB::table('model_has_roles')->insert([
            'role_id' => $role->id,
            'model_type' => get_class($admin1),
            'model_id' => $admin1->id,
            'company_id' => $company1->id,
        ]);

        /** ================================================== */

        $admin2 = User::create([
            'name' => 'Admin PT. Dua',
            'email' => 'admin2@gmail.com',
            'password' => bcrypt('12345678'),
            'level' => UserLevelEnum::ADMIN,
        ]);

        $company2 = Company::create([
            'name' => 'PT. Dua',
        ]);

        $tenant1 = Tenant::create([
            'company_id' => $company2->id,
            'name' => 'Tenant 1 PT Dua',
        ]);

        $tenant2 = Tenant::create([
            'company_id' => $company2->id,
            'name' => 'Tenant 2 PT Dua',
        ]);

        $admin2->companies()->sync([$company2->id]);
        $admin2->tenants()->sync([$tenant1->id, $tenant2->id]);

        DB::table('model_has_roles')->insert([
            'role_id' => $role->id,
            'model_type' => get_class($admin2),
            'model_id' => $admin2->id,
            'company_id' => $company2->id,
        ]);
    }
}
