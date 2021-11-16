<?php

namespace App\Http\Resources\WebResources;

use App\Models\UserModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class TemplateEmailListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'default' => $this->default,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_user' => $this->createdUser ? [
                'id' => $this->createdUser->id,
                'name' => $this->createdUser->full_name,
                'email' => $this->createdUser->email,
            ] : null,
            'cc_list' => $this->cc,
            'bcc_list' => $this->bcc,
        ];
    }
}
