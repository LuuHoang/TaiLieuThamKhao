<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "is_admin" => $this->is_admin,
            "is_default" => $this->is_default,
            "permissions" => $this->permissions
        ];
    }
}
