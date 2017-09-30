<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketSort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_sort', function (Blueprint $table) {
            $table->integer('ticket_id')->unsigned();
            $table->integer('daily_sort_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('daily_sort_id')->references('id')->on('daily_sort');
            $table->primary(['ticket_id', 'daily_sort_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_sort');
    }
}
