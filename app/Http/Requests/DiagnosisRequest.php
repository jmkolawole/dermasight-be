<?php

namespace App\Http\Requests;


class DiagnosisRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'string|nullable',
            'skin_issue_description' => 'required|string',
        ];
    }
}
