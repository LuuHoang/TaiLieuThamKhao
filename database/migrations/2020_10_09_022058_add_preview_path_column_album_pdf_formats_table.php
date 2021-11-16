<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreviewPathColumnAlbumPdfFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album_pdf_formats', function (Blueprint $table) {
            $table->string('preview_cover_path')->nullable();
            $table->string('preview_content_path')->nullable();
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
            $table->dropColumn(['preview_cover_path', 'preview_content_path']);
        });
    }
}
