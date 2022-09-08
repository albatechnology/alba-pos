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
            $table->foreignId('order_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('tenant_id')->nullable();
            $table->foreignId('payment_type_id')->nullable();
            $table->foreignId('added_by_id')->nullable();
            $table->foreignId('approved_by_id')->nullable();
            $table->string('status', 30); // enum
            $table->float('value');
            $table->text('note');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id', 'tenant_id', 'company_id', 'payment_type_id', 'added_by_id', 'approved_by_id']);
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
