<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('decks', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name');
        //     $table->string('hidden_face_image_path');
        //     $table->boolean('active')->default(true);
        //     $table->boolean('complete')->default(false);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::dropIfExists('decks');
    }
}
