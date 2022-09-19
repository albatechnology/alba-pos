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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->index();
            $table->foreignId('company_id')->nullable()->index();
            $table->foreignId('tenant_id')->nullable()->index();
            $table->foreignId('payment_type_id')->nullable()->index();
            $table->foreignId('added_by_id')->nullable()->index();
            $table->foreignId('approved_by_id')->nullable()->index();
            $table->string('status', 30); // enum
            $table->float('value')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->index(['order_id', 'tenant_id', 'company_id', 'payment_type_id', 'added_by_id', 'approved_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
