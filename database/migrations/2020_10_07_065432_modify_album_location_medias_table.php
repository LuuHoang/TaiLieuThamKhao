<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAlbumLocationMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_location_medias', function (Blueprint $table) {
            $table->string('image_after_path')->nullable();
            $table->unsignedBigInteger('before_size')->default(0);
            $table->unsignedBigInteger('after_size')->default(0);
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
            $table->dropColumn(['image_after_path', 'after_size', 'before_size']);
        });
    }
}
