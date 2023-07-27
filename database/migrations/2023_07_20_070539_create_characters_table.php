<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->Integer('film_id')->nullable();
            $table->String('character')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
