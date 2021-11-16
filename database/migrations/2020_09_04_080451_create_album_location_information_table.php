<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumLocationInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_location_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('album_location_id');
            $table->foreign('album_location_id')->references('id')->on('album_locations');
            $table->unsignedBigInteger('location_property_id');
            $table->foreign('location_property_id')->references('id')->on('location_properties');
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
        Schema::dropIfExists('album_location_information');
    }
}
