<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtendPackageIdToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('contracts', 'extend_package_id')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->integer('extend_package_id')->nullable();
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
        if (Schema::hasColumn('contracts', 'extend_package_id')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropColumn('extend_package_id');
            });
        }
    }
}
