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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->index();
            $table->foreignId('company_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->index();
            $table->foreignId('customer_id')->nullable()->index();
            $table->foreignId('discount_id')->nullable()->index();
            $table->string('invoice_number')->unique()->index()->nullable();
            $table->string('status', 30); // enum
            $table->string('payment_status', 30); // enum
            $table->integer('total_discount')->default(0)->comment('total discount of discount selected');
            $table->integer('additional_discount')->default(0)->comment('manual additional discount');
            $table->integer('amount_paid')->default(0)->comment('amount paid of total_price');
            $table->integer('total_tax')->default(0)->comment('SUM of total_tax from order_details');
            $table->integer('original_price')->default(0)->comment('SUM of original_price from order_details');
            $table->integer('total_price')->default(0)->comment('SUM of total_price from order_details - (additional_discount + discount selected)');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->index(['tenant_id', 'company_id', 'user_id', 'customer_id', 'discount_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
