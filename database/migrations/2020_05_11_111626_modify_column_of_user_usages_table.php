<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnOfUserUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_usages', function (Blueprint $table) {
            $table->unsignedBigInteger('count_data')->change();
            $table->unsignedBigInteger('package_data')->change();
            $table->unsignedBigInteger('extend_data')->change();
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
            $table->unsignedInteger('count_data')->change();
            $table->unsignedInteger('package_data')->change();
            $table->unsignedInteger('extend_data')->change();
        });
    }
}
