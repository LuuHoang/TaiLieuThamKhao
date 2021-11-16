<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTypeColumnSizeOfAlbumLocationMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_location_medias', function (Blueprint $table) {
            $table->unsignedBigInteger('size')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('album_location_medias', function (Blueprint $table) {
            $table->unsignedInteger('size')->change();
        });
    }
}
