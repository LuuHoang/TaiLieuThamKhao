<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDescriptionColumnOfAlbumPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_properties', function (Blueprint $table) {
            $table->text('description')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('album_properties', function (Blueprint $table) {
            $table->dropColumn('description');
        });
        Schema::table('album_properties', function (Blueprint $table) {
            $table->text('description')->after('title');
        });
    }
}
