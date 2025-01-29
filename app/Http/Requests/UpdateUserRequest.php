<?php

namespace App\Http\Requests;

class UpdateUserRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'password' => 'sometimes|required_with:password_confirmation',
            'password_confirmation' => 'required_with:password|same:password',
            'firstname' => 'sometimes',
            'lastname' => 'sometimes',
        ];
                
    }
}
