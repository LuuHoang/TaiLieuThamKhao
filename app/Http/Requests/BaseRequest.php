<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * Class BaseRequest
 */
class BaseRequest extends FormRequest
{
    /**
     * @param Validator $validator
     *
     * @throws HttpResponseException
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson() || $this->ajax()) {
            $this->failedValidationWithJson($validator);
        } else {
            parent::failedValidation($validator);
        }
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidationWithJson(Validator $validator)
    {
        throw new ValidationException($validator);
    }
}
