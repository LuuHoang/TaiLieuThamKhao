<?php

use App\Constants\CommentCreator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumLocationMediaCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_location_media_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('album_location_media_id');
            $table->foreign('album_location_media_id')->references('id')->on('album_location_medias')->onDelete('cascade');
            $table->unsignedBigInteger('creator_id');
            $table->integer('creator_type')->comment(CommentCreator::USER . ': User | '. CommentCreator::SHARE_USER .': Share User');
            $table->string('content');
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
        Schema::dropIfExists('album_location_media_comments');
    }
}
