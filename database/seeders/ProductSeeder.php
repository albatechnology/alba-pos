<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productBrand = ProductBrand::create([
            'company_id' => 2,
            'name' => 'Product Brand PT 1',
        ]);

        $productCategory = ProductCategory::create([
            'company_id' => 2,
            'name' => 'Product Category PT 1',
        ]);

        $faker = Faker::create('id_ID');
        for ($i = 1; $i <= 50; $i++) {

            $product = Product::create([
                'company_id' => 2,
                'product_brand_id' => $productBrand->id,
                'code' => Str::random(15),
                'name' => $faker->name,
                'uom' => 1,
                'price' => 50000,
                'tax' => 1000,
            ]);

            $product->productCategories()->attach($productCategory->id);
        }

        //

        $productBrand2 = ProductBrand::create([
            'company_id' => 2,
            'name' => 'Product Brand PT 1',
        ]);

        $productCategory2 = ProductCategory::create([
            'company_id' => 3,
            'name' => 'Product Category PT 2',
        ]);

        for ($i = 1; $i <= 50; $i++) {

            $product = Product::create([
                'company_id' => 3,
                'product_brand_id' => $productBrand2->id,
                'code' => Str::random(15),
                'name' => $faker->name,
                'uom' => 1,
                'price' => 50000,
                'tax' => 1000,
            ]);

            $product->productCategories()->attach($productCategory2->id);
        }
    }
}
