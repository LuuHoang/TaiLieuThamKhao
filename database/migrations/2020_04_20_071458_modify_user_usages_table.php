<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_usages', function (Blueprint $table) {
            $table->unsignedInteger('package_data');
            $table->unsignedInteger('extend_data')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_usages', function (Blueprint $table) {
            $table->dropColumn(['package_data', 'extend_data']);
        });
    }
}
