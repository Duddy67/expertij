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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status', 20);
            $table->char('access_level', 10)->default('public_ro');
            $table->boolean('member_list')->default(1);
            $table->boolean('sending_emails')->default(0);
            $table->timestamp('member_since')->nullable();
            $table->string('member_number', 30)->nullable();
            $table->boolean('associated_member')->default(0);
            $table->boolean('free_period')->default(0);
            $table->char('insurance_code', 3)->nullable();
            $table->string('professional_status', 20)->nullable();
            $table->string('professional_status_info', 30)->nullable();
            $table->unsignedSmallInteger('since')->nullable();
            $table->string('siret_number', 14)->nullable();
            $table->string('naf_code', 5)->nullable();
            $table->text('linguistic_training')->nullable();
            $table->text('extra_linguistic_training')->nullable();
            $table->text('professional_experience')->nullable();
            $table->text('observations')->nullable();
            $table->text('why_expertij')->nullable();
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
        Schema::dropIfExists('memberships');
    }
};
