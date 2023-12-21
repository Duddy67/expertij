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
        Schema::create('membership_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->char('choice', 3);
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('membership_votes');
    }
};
