<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidelineInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guideline_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('guideline_id');
            $table->foreign('guideline_id')->references('id')->on('guidelines');
            $table->string('title');
            $table->text('content')->nullable();
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
        Schema::dropIfExists('guideline_information');
    }
}
