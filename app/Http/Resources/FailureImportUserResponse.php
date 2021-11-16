<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FailureImportUserResponse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'row' => $this->row(),
            'attribute' => $this->attribute(),
            'errors' => $this->errors()
        ];
    }
}
