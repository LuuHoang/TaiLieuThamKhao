<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ListAdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    =>    $this->id,
            'full_name'             =>    $this->full_name,
        ];
    }
}
