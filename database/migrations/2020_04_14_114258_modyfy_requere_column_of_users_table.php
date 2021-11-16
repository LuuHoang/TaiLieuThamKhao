<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModyfyRequereColumnOfUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_code', 8)->nullable()->change();
            $table->string('avatar_path')->nullable()->change();
            $table->unsignedBigInteger('department_id')->nullable()->change();
            $table->unsignedBigInteger('position_id')->nullable()->change();
            $table->string('address')->after('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_code', 8)->nullable(false)->change();
            $table->string('avatar_path')->nullable(false)->change();
            $table->unsignedBigInteger('department_id')->nullable(false)->change();
            $table->unsignedBigInteger('position_id')->nullable(false)->change();
            $table->string('address')->after('email')->nullable(false)->change();
        });
    }
}
