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
        Schema::table('users', function(Blueprint $table)
	{
            $table->char('civility', 3)->nullable()->after('email');
            $table->string('first_name', 30)->after('civility');
            $table->string('last_name', 30)->after('first_name');
            $table->string('birth_name', 30)->nullable()->after('last_name');
            $table->date('birth_date')->nullable()->after('birth_name');
            $table->string('birth_location', 30)->nullable()->after('birth_date');
            $table->char('citizenship_id', 3)->nullable()->after('birth_location');
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
	{
	    $table->dropColumn('civility');
	    $table->dropColumn('first_name');
	    $table->dropColumn('last_name');
	    $table->dropColumn('birth_name');
	    $table->dropColumn('birth_date');
	    $table->dropColumn('birth_location');
	    $table->dropColumn('citizenship_id');
	});
    }
};
