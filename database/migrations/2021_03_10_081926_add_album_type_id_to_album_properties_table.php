<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlbumTypeIdToAlbumPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('album_properties','album_type_id')) {
            Schema::table('album_properties', function (Blueprint $table) {
                $table->integer('album_type_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('album_properties','album_type_id')) {
            Schema::table('album_properties', function (Blueprint $table) {
                $table->dropColumn('album_type_id');
            });
        }
    }
}
