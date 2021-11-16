<?php

namespace App\Http\Resources;

use App\Constants\Disk;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PdfFileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file_path' => Storage::disk(Disk::PDF)->url($this->path),
        ];
    }
}
