<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageSupportDescriptionToAppVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_versions', function (Blueprint $table) {
            $table->renameColumn('description', 'en_description');
            $table->text('ja_description')->nullable();
            $table->text('vi_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_versions', function (Blueprint $table) {
            $table->renameColumn('en_description', 'description');
            $table->dropColumn(['ja_description', 'vi_description']);
        });
    }
}
