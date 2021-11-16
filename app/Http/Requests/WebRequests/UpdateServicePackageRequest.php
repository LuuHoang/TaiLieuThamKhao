<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateServicePackageRequest extends BaseRequest
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
            'max_user'      =>  'integer|min:1',
            'max_user_data' =>  'integer|min:1',
            'price'         =>  'integer|min:1'
        ];
    }
}
