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
            $table->float('unit_price')->default(0);
            $table->float('quantity')->default(0);
            $table->float('total_discount')->default(0);
            $table->float('total_price')->default(0);
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
