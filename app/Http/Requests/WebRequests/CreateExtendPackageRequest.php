<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateExtendPackageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         =>  'required|string|max:255',
            'description'   =>  'nullable|string|max:255',
            'extend_user'   =>  'required|integer|min:0',
            'extend_data'   =>  'required|integer|min:1',
            'price'         =>  'required|integer|min:1'
        ];
    }
}
