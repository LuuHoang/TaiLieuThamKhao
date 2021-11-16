<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAlbumPdfFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_pdf_formats', function (Blueprint $table) {
            $table->string('cover_path');
            $table->string('content_path');
            $table->unsignedInteger('number_images');
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
            $table->dropColumn(['cover_path', 'content_path', 'number_images']);
        });
    }
}
