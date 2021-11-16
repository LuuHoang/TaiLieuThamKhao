<?php


namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class UpdateLocationPropertyRequest extends BaseRequest
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
    public function messages()
    {
        return [
            'require.required'  => 'messages.location_property_require_is_required',
            'display.required'  => 'messages.location_property_display_is_required',
            'highlight.required'  => 'messages.location_property_highlight_is_required',
        ];
    }
}
