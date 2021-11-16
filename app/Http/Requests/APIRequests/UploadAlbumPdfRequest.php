<?php

namespace App\Http\Requests\APIRequests;

use App\Http\Requests\BaseRequest;

class UploadAlbumPdfRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required|array|max:5',
            'files.*' => 'file|mimes:pdf|max:10240', // 10mb
        ];
    }
}
