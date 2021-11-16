<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumConfigStampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_stamp_configs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('stamp_type');
            $table->integer('mounting_position');
            $table->string('icon_path');
            $table->unsignedBigInteger('company_id');
            $table->string('text');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_stamp_configs');
    }
}
