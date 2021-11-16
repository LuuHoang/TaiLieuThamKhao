<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class UpdateMediaPropertyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display'   =>  'required|boolean',
            'highlight' =>  'required|boolean',
            'description' => 'nullable|string',
            'require' => 'required|boolean',

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
}
