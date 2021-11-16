<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumLocationMediaInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_location_media_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('album_location_media_id');
            $table->foreign('album_location_media_id')->references('id')->on('album_location_medias');
            $table->unsignedBigInteger('media_property_id');
            $table->foreign('media_property_id')->references('id')->on('media_properties');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('album_location_media_information');
    }
}
