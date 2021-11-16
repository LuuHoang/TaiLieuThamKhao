<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumLocationMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_location_medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('album_location_id');
            $table->foreign('album_location_id')->references('id')->on('album_locations');
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->string('comment')->nullable();
            $table->tinyInteger('type');
            $table->unsignedInteger('size');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('album_location_medias');
    }
}
