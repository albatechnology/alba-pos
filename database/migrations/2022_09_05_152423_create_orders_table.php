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
            $table->foreignId('tenant_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('discount_id')->nullable();
            $table->string('invoice_number')->unique();
            $table->string('status', 30); // enum
            $table->string('payment_status', 30); // enum
            $table->float('total_discount')->default(0);
            $table->float('total_price')->default(0);
            $table->float('amount_paid')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'company_id', 'user_id', 'customer_id', 'discount_id']);
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
