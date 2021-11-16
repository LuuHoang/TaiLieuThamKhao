<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlbumTypeIdAndDescriptionToMediaPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('media_properties','album_type_id')) {
            Schema::table('media_properties', function (Blueprint $table) {
                $table->integer('album_type_id')->nullable();
            });
        }
        if(!Schema::hasColumn('media_properties','description')) {
            Schema::table('media_properties', function (Blueprint $table) {
                $table->text('description')->default(0);
            });
        }
        if(!Schema::hasColumn('media_properties','require')) {
            Schema::table('media_properties', function (Blueprint $table) {
                $table->text('require')->default(0);
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
        if(Schema::hasColumn('media_properties','album_type_id')) {
            Schema::table('media_properties', function (Blueprint $table) {
                $table->dropColumn('album_type_id');
            });
        }
        if(Schema::hasColumn('media_properties','description')) {
            Schema::table('media_properties', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
        if(Schema::hasColumn('media_properties','require')) {
            Schema::table('media_properties', function (Blueprint $table) {
                $table->dropColumn('require');
            });
        }
    }
}
