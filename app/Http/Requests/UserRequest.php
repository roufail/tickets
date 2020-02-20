<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email,'.request()->segment(4),
            'name' => 'required|unique:users,name,'.request()->segment(4),
        ];

        if(request()->method() != 'PUT' && request()->method() != 'put')
        {
            // new user
            $rules['password'] = 'required|min:6';
            $rules['confirm_password'] = 'required|min:6|same:password';
        } else {
            $rules['password'] = 'nullable|required_with:confirm_password|min:6';
            $rules['confirm_password'] = 'nullable|required_with:confirm_password|same:password|min:6';
        }

        if(request()->segment(4) != 1) {
            $rules['role'] = 'required|exists:roles,id';
        }

        return $rules;

    }
}
