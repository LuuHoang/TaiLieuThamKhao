<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateExtendPackageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         =>  'string|max:255',
            'description'   =>  'nullable|string|max:255',
            'extend_user'   =>  'integer|min:0',
            'extend_data'   =>  'integer|min:1',
            'price'         =>  'integer|min:1'
        ];
    }
}
