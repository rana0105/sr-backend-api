<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleRequest extends FormRequest
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
        if (Request::route()->getName() === "roles.store") {
            return [
                'name' => 'required|unique:roles,name'
            ];
        }

        if (Request::route()->getName() === "roles.update") {
            return [
                'name' => 'required|unique:roles,name,' . $this->id
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
