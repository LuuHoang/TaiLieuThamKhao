<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentPageIdToAlbumPdfFormats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_pdf_formats', function (Blueprint $table) {
            $table->integer('content_page_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('album_pdf_formats', function (Blueprint $table) {
            $table->dropColumn('content_page_id');
        });
    }
}
