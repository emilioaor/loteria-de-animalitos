<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAnimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 15);
            $table->string('number', 3);
            $table->integer('sort_id')->unsigned();
            $table->foreign('sort_id')->references('id')->on('sorts');
            $table->timestamps();
        });

        Schema::create('animal_ticket', function (Blueprint $table) {
            $table->integer('animal_id')->unsigned();
            $table->integer('ticket_id')->unsigned();
            $table->float('amount');
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->primary(['ticket_id', 'animal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_ticket');
        Schema::dropIfExists('animals');
    }
}
