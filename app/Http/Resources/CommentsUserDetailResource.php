<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-05
 * Time: 10:40
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CommentsUserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                    =>      $this->id,
            "full_name"             =>      $this->full_name,
            "email"                 =>      $this->email,
            "address"               =>      $this->address,
            "avatar_path"           =>      $this->avatar_path,
            "avatar_url"            =>      $this->avatar_url,
        ];
    }
}