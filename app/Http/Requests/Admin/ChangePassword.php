<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,12',
            'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,12',
            'confirm_password' => 'required|same:password|between:6,12|regex:/^[a-zA-Z0-9]+$/',
        ];
    }
    public function messages()
    {
        return [
            "current_password.regex" => __("response.length_password"),
            "current_password.between" => __("response.length_password"),
            "password.required" => __("response.new_password"),
            "password.regex" => __("response.length_password"),
            "password.between" => __("response.length_password"),
            "confirm_password.required" => __("response.new_password_confirm"),
            "confirm_password.regex" => __("response.length_password"),
            "confirm_password.between" => __("response.length_password"),
            "confirm_password.same" => __("response.same_password"),
        ];
    }
}
