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
        Schema::create('membership_licences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_id')->nullable();
            $table->unsignedBigInteger('jurisdiction_id')->nullable();
            $table->string('type', 20);
            $table->timestamp('attestation_expiry_date')->nullable();
            $table->unsignedSmallInteger('since');
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
        Schema::dropIfExists('membership_licences');
    }
};
