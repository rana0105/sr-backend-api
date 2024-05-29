<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (Request::route()->getName() === "users.store") {
            return [
                'name' => 'required',
                'phone' => 'required|unique:users,phone',
                'user_code' => 'required|unique:users,user_code',
                'email' => 'required|email|unique:users,email',
                'role_id' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required|same:password'
            ];
        }

        if (Request::route()->getName() === "users.update") {
            return [
                'name' => 'required',
                'phone' => 'required|unique:users,phone,' . $this->id,
                'email' => 'required|email|unique:users,email,' . $this->id,
                'role_id' => 'required'
            ];
        }

        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "errors" => $validator->errors(),
            "messages" => $validator->messages("*")->first()
        ], 400));
    }
}
