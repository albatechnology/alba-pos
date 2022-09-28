<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductTenant;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productTenants = ProductTenant::all();
        foreach ($productTenants as $product) {
            Stock::updateOrCreate(
                [
                    'tenant_id' => $product->tenant_id,
                    'product_id' => $product->product_id
                ],
                [
                    'company_id' => $product->product->company_id,
                    'stock' => 100
                ]
            );
        }
    }
}
