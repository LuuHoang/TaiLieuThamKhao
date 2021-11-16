<?php


namespace App\Http\Requests\WebRequests;


use App\Http\Requests\BaseRequest;

class UpdateAdminRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->id = (int)$this->id;
        return [
            'full_name' => "required|string|max:255",
            'email'     => "nullable|email|max:255|unique:admins,email,{$this->id},id",
            'password' =>  "nullable|string|min:6|max:50",
            'avatar' =>    "nullable|image|mimes:jpeg,png,jpg|max:2048",
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
            'full_name.required' => 'messages.full_name_is_required',
            'password.min'       => 'messages.weak_password',
            'password.max'       => 'password.password_is_to_long',
            'avatar.image'       => 'messages.avatar_is_image',
        ];
    }
}
