<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidelineInformationMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guideline_information_medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('guideline_information_id');
            $table->foreign('guideline_information_id')->references('id')->on('guideline_information');
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->tinyInteger('type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guideline_information_medias');
    }
}
