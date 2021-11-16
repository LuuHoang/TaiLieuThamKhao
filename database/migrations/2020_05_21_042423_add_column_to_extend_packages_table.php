<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToExtendPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extend_packages', function (Blueprint $table) {
            $table->unsignedInteger('extend_user')->default(0);
            $table->String('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extend_packages', function (Blueprint $table) {
            $table->dropColumn('extend_user');
            $table->dropColumn('description');
        });
    }
}
