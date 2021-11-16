<?php

namespace App\Http\Resources;

use App\Constants\App;
use App\Constants\CommentCreator;
use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AlbumLocationMediaForShareUserResource extends JsonResource
{
    public function toArray($request)
    {
//        $shareUserEmail = app('shareUserEmail');
        $commonService = app(CommonService::class);
        $handleResourceService = app(HandleResourceService::class);
//        $commentsPublic = new Collection([]);
//        $allComment = $this->comments;
//        foreach ($allComment as $comment) {
//            if ($comment->creator_type === CommentCreator::USER) {
//                if ($commonService->checkSettingCommentPublic($comment->user) || $this->albumLocation->album->user_id === $comment->creator_id) {
//                    $commentsPublic->push($comment);
//                }
//            } else {
//                if ($comment->shareUser->email === $shareUserEmail) {
//                    $commentsPublic->push($comment);
//                }
//            }
//        }
        return [
            "id"                => $this->id,
            "path"              => $this->path,
            "url"               => $this->url,
            "image_after_path"  => $this->image_after_path,
            "image_after_url"   => $this->image_after_url,
            "thumbnail_path"    => $this->thumbnail_path,
            "thumbnail_url"     => $this->thumbnail_url,
            "album_location_id" => $this->album_location_id,
            "description"       => $this->description,
            "created_time"      => $this->created_time,
            "information"       => $handleResourceService->handleAlbumLocationMediaInformationBlankResource($this->mediaInformation, $this->albumLocation->album->user->company->mediaProperties),
            "comments"          => $commonService->showComment($this->albumLocation->album) ? AlbumLocationMediaCommentResource::collection($this->comments->take(App::NUMBER_COMMENT_PREVIEW)->reverse()->values()) : [],
            "number_comment"    => $commonService->showComment($this->albumLocation->album) ? $this->comments->count() : 0,
            "type"              => $this->type,
            "size"              => $handleResourceService->convertByteToGigaByte($this->size),
            "updated_at"        => $this->updated_at,
            "show_comment"      =>  $commonService->showComment($this->albumLocation->album)
        ];
    }
}
