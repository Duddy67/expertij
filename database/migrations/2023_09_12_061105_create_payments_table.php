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
            $table->unsignedInteger('payable_id')->nullable();
            $table->string('payable_type')->nullable();
            $table->char('status', 15);
            $table->char('mode', 15);
            $table->char('item', 25);
            $table->decimal('amount', $precision = 5, $scale = 2);
            $table->char('currency', 5);
            $table->text('message')->nullable();
            $table->text('data')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
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
