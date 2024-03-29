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
        Schema::create('membership_sharings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->tinyText('description')->nullable();
            $table->char('licence_types', 20);
            $table->string('courts', 100);
            $table->string('appeal_courts', 100);
            $table->boolean('sending_emails')->default(0);
            $table->char('status', 12);
            $table->char('access_level', 10);
            $table->unsignedBigInteger('checked_out')->nullable();
            $table->timestamp('checked_out_time')->nullable();
            $table->unsignedBigInteger('owned_by');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('membership_sharings');
    }
};
