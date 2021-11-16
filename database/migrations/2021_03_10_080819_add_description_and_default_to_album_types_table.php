<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionAndDefaultToAlbumTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('album_types', 'description')) {
            Schema::table('album_types', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }
        if(!Schema::hasColumn('album_types', 'default')){
            Schema::table('album_types', function (Blueprint $table) {
                $table->tinyInteger('default')->default(0);
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
        if (Schema::hasColumn('album_types', 'description')) {
            Schema::table('album_types', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
        if (Schema::hasColumn('album_types', 'default')) {
            Schema::table('album_types', function (Blueprint $table) {
                $table->dropColumn('default');
            });
        }
    }
}
