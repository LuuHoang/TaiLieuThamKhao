<?php

namespace App\Http\Resources\WebResources;

use App\Constants\CommentCreator;
use App\Http\Resources\ShareUserDetailResource;
use App\Http\Resources\ShortUserDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TopAlbumLocationMediaCommentResource extends JsonResource
{
    public function toArray($request)
    {
        $creator = null;
        if ($this->creator_type == CommentCreator::USER) {
            $creator = new ShortUserDetailResource($this->user);
        } elseif ($this->creator_type == CommentCreator::SHARE_USER) {
            $creator = new ShareUserDetailResource($this->shareUser);
        }
        $albumLocationMedia = $this->albumLocationMedia;
        $albumLocation = $albumLocationMedia->albumLocation;
        $album = $albumLocation->album;
        return [
            "id"                    => $this->id,
            "creator"               => $creator,
            "creator_type"          => $this->creator_type,
            "content"               => $this->content,
            "media"                 => [
                "id"                =>  $albumLocationMedia->id,
                "location"          => [
                    "id"            => $albumLocation->id,
                    "title"         => $albumLocation->title,
                    "album"         => [
                        "id"        => $album->id,
                        "highlight" => $album->highlightInformation->value
                    ]
                ]
            ],
            "create_at"             => $this->created_at
        ];
    }
}
