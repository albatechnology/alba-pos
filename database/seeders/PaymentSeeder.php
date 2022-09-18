<?php

namespace Database\Seeders;

use App\Models\PaymentCategory;
use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentCategoryCash = PaymentCategory::create([
            'company_id' => 2,
            'name' => 'Cash'
        ]);

        $paymentCategoryTf = PaymentCategory::create([
            'company_id' => 2,
            'name' => 'Transfer'
        ]);

        PaymentType::create([
            'payment_category_id' => $paymentCategoryCash->id,
            'name' => 'Cash',
            'company_id' => 2
        ]);

        PaymentType::create([
            'payment_category_id' => $paymentCategoryTf->id,
            'name' => 'Transfer BCA',
            'company_id' => 2
        ]);

        PaymentType::create([
            'payment_category_id' => $paymentCategoryTf->id,
            'name' => 'Transfer Mandiri',
            'company_id' => 2
        ]);
    }
}
