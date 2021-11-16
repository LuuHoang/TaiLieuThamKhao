<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-05
 * Time: 14:30
 */

namespace App\Http\Resources;


use App\Constants\CommentCreator;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationMediaCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $creator = null;
        if ($this->creator_type == CommentCreator::USER) {
            $creator = new ShortUserDetailResource($this->user);
        } elseif ($this->creator_type == CommentCreator::SHARE_USER) {
            $creator = new ShareUserDetailResource($this->shareUser);
        }
        return [
            "id"            => $this->id,
            "creator"       => $creator,
            "creator_type"  => $this->creator_type,
            "content"       => $this->content,
            "create_at"     => $this->created_at,
            "update_at"     => $this->updated_at
        ];
    }
}
