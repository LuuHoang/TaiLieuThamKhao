<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateServicePackageRequest extends BaseRequest
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
            'max_user'      =>  'required|integer|min:1',
            'max_user_data' =>  'required|integer|min:1',
            'price'         =>  'required|integer|min:1'
        ];
    }
}
