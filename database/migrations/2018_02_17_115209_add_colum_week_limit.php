<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumWeekLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sorts', function(Blueprint $table) {
            $table->addColumn('float', 'week_limit')->nullable()->after('daily_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sorts', function(Blueprint $table) {
            $table->dropColumn('week_limit');
        });
    }
}
