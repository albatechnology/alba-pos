<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tenant_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->integer('unit_price')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('total_discount')->default(0);
            $table->integer('total_tax')->default(0)->comment('total tax per product');
            $table->integer('original_price')->default(0)->comment('(quantity * unit_price)');
            $table->integer('total_price')->default(0)->comment('original_price + total_tax - total_discount');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id', 'tenant_id', 'company_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
