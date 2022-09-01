<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
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
    }
}
