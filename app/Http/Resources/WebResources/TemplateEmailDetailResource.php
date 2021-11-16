<?php

namespace App\Http\Resources\WebResources;

class TemplateEmailDetailResource extends TemplateEmailListResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'content' => $this->content,
            'subject' => $this->subject,
            'updated_user' => $this->updatedUser ? [
                'id' => $this->updatedUser->id,
                'name' => $this->updatedUser->full_name,
                'email' => $this->updatedUser->email,
            ] : null,
        ]);
    }
}
