<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractCodeToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('contracts', 'contract_code')) {
                Schema::table('contracts', function (Blueprint $table) {
                    $table->string('contract_code',10)->nullable();
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
            if (Schema::hasColumn('contracts', 'contract_code')) {
                Schema::table('contracts', function (Blueprint $table) {
                    $table->dropColumn('contract_code');
                });
            }
        });
    }
}
