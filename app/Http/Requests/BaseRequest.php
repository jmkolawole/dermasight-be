<?php

namespace App\Http\Requests;

use App\Traits\SendsApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    use SendsApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Failed validation handler
     */
    public function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->failure($validator->errors()->toArray(), 401));
    }
}
