<?php

namespace App\Http\Requests\APIRequests;

use App\Http\Requests\BaseRequest;

class CheckModifyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'updated_at' => 'required|string'
        ];
    }
}
