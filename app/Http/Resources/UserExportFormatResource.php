<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserExportFormatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'staff_code' => $this->staff_code,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'address' => $this->address,
            'department' => $this->department ?? null,
            'position' => $this->position ?? null,
            'role' => $this->userRole->name ?? null,
            'user_manager' => $this->userCreated->email ?? null
        ];
    }
}
