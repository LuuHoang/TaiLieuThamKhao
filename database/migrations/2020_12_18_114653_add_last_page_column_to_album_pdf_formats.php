<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastPageColumnToAlbumPdfFormats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_pdf_formats', function (Blueprint $table) {
            $table->longText('last_page')->nullable();
            $table->string('last_path')->nullable();
            $table->string('preview_last_path')->nullable();
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
            $table->dropColumn(['last_page', 'last_path', 'preview_last_path']);
        });
    }
}
