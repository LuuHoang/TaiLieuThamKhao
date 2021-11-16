<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValueListToAlbumInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('album_informations', 'value_list')) {
            Schema::table('album_informations', function (Blueprint $table) {
                $table->string('value_list')->nullable();
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
        if (Schema::hasColumn('album_informations', 'value_list')) {
            Schema::table('album_informations', function (Blueprint $table) {
                $table->dropColumn('value_list');
            });
        }
    }
}
