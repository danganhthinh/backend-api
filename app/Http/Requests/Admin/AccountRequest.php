<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'full_name' => 'required|max:255',
            // 'birthday' => 'date_format:d/m/Y|before:tomorrow',
            'student_id' => 'required|digits:4|numeric',
            "expired_at" => "required|date_format:d/m/Y|after:yesterday",
        ];
    }
    public function messages()
    {
        return [
            "student_id.digits" => __("response.student_id"),
            "student_id.numeric" => __("response.student_id"),
            "expired_at.after" => __("response.expired_at_after"),
        ];
    }
}
