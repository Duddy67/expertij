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
        Schema::create('membership_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attestation_id')->nullable();
            $table->char('language_id', 3)->nullable();
            $table->boolean('interpreter')->nullable();
            $table->boolean('interpreter_cassation')->nullable();
            $table->boolean('translator')->nullable();
            $table->boolean('translator_cassation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_skills');
    }
};
