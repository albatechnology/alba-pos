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
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->integer('changes');
            $table->integer('old_amount');
            $table->integer('new_amount');
            $table->string('source', 30);
            $table->timestamps();

            $table->index(['stock_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_histories');
    }
};
