<?php

namespace App\Http\Resources;

use App\Constants\App;
use App\Constants\CommentCreator;
use App\Constants\Disk;
use App\Constants\Media;
use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AlbumLocationMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currentUser = app('currentUser');
        $commonService = app(CommonService::class);
        $handleResourceService = app(HandleResourceService::class);
//        $commentsPublic = new Collection([]);
//        $allComment = $this->comments;
//        if ($commonService->isAdmin($currentUser) || $currentUser->id === $this->albumLocation->album->user_id || $currentUser->id === $this->albumLocation->album->user->user_created_id) {
//            $commentsPublic = $allComment;
//        } else {
//            foreach ($allComment as $comment) {
//                if ($comment->creator_type === CommentCreator::USER
//                    && ($commonService->checkSettingCommentPublic($comment->user)
//                        || $this->albumLocation->album->user_id === $comment->creator_id
//                        || $currentUser->id === $comment->creator_id
//                        || (!$commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))
//                            && $commonService->isSubUser(json_decode($comment->user->userRole->permissions ?? '[]', true))
//                            && in_array($comment->creator_id, $currentUser->subUsers->pluck('id')->values()->toArray())))) {
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
            "show_comment"      =>  $commonService->showComment($this->albumLocation->album),
            "allow_update"      =>  $commonService->allowUpdateAlbum($currentUser, $this->albumLocation->album)
        ];
    }
}
