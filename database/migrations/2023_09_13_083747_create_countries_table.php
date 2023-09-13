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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('countryable_id')->nullable();
            $table->string('countryable_type')->nullable();
            $table->char('alpha_2', 2);
            $table->char('alpha_3', 3);
            $table->unsignedSmallInteger('numerical');
            $table->char('continent_code', 2);
            $table->boolean('published');
            $table->tinyText('fr');
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
        Schema::dropIfExists('countries');
    }
};
