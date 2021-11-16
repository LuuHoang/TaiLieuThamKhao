<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCompanyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_usages', function (Blueprint $table) {
            $table->dropColumn('count_data');
            $table->unsignedInteger('count_extend_data')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_usages', function (Blueprint $table) {
            $table->unsignedInteger('count_data');
            $table->dropColumn('count_extend_data');
        });
    }
}
