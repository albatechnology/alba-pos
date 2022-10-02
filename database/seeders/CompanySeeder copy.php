<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeederCopy extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'id' => 1,
            'name' => 'PT. Alba Digital Teknologi',
        ]);
        Company::create([
            'id' => 2,
            'name' => 'PT. Satu',
        ]);
        Company::create([
            'id' => 3,
            'name' => 'PT. Dua',
        ]);

        Tenant::insert([
            [
                'company_id' => 2,
                'name' => 'Tenant 1 PT Satu',
            ],
            [
                'company_id' => 2,
                'name' => 'Tenant 2 PT Satu',
            ],
            [
                'company_id' => 3,
                'name' => 'Tenant 1 PT Dua',
            ],
            [
                'company_id' => 3,
                'name' => 'Tenant 2 PT Dua',
            ],
        ]);
    }
}
