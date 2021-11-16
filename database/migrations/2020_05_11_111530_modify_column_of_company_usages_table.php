<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnOfCompanyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_usages', function (Blueprint $table) {
            $table->unsignedBigInteger('count_data')->change();
            $table->unsignedBigInteger('count_extend_data')->change();
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
            $table->unsignedInteger('count_data')->change();
            $table->unsignedInteger('count_extend_data')->change();
        });
    }
}
