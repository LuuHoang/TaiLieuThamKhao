<?php

namespace App\Http\Resources;

use App\Constants\App;
use App\Constants\CommentCreator;
use App\Constants\Disk;
use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AlbumLocationResource extends JsonResource
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
        $albumLocationImageLatest = $this->albumLocationImageLatest;
//        $commentsPublic = new Collection([]);
//        $allComment = $this->comments;
//        if ($commonService->isAdmin($currentUser) || $currentUser->id === $this->album->user_id || $currentUser->id === $this->album->user->user_created_id) {
//            $commentsPublic = $allComment;
//        } else {
//            foreach ($allComment as $comment) {
//                if ($comment->creator_type === CommentCreator::USER
//                    && ($commonService->checkSettingCommentPublic($comment->user)
//                        || $this->album->user_id === $comment->creator_id
//                        || $currentUser->id === $comment->creator_id
//                        || (!$commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))
//                            && $commonService->isSubUser(json_decode($comment->user->userRole->permissions ?? '[]', true))
//                            && in_array($comment->creator_id, $currentUser->subUsers->pluck('id')->values()->toArray())))) {
//                    $commentsPublic->push($comment);
//                }
//            }
//        }
        return [
            "id"                =>  $this->id,
            "title"             =>  $this->title,
            "description"       =>  $this->description,
            "information"       =>  $handleResourceService->handleAlbumLocationInformationBlankResource($this->locationInformation, $this->album->user->company->locationProperties),
            "location_image"    =>  $albumLocationImageLatest ? Storage::disk(Disk::ALBUM)->url($albumLocationImageLatest->path) : "",
            "comments"          =>  $commonService->showComment($this->album) ? AlbumLocationCommentResource::collection($this->comments->take(App::NUMBER_COMMENT_PREVIEW)->reverse()->values()) : [],
            "number_comment"    =>  $commonService->showComment($this->album) ? $this->comments->count() : 0,
            "created_at"        =>  $this->created_at,
            "ts_created_at"     =>  strtotime($this->created_at),
            "medias"            =>  AlbumLocationMediaResource::collection($this->albumLocationMedias),
            "updated_at"        => $this->updated_at,
            "show_comment"      =>  $commonService->showComment($this->album),
            "allow_update"      =>  $commonService->allowUpdateAlbum($currentUser, $this->album)
        ];
    }
}
