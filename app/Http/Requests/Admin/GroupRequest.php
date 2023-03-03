<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST': {
                    $rule = [
                        'name' => 'required|max:255',
                        'code' => 'required|size:3|regex:/^[a-zA-Z0-9]+$/|unique:schools,code,NULL,id,deleted_at,NULL|unique:groups,code,NULL,id,deleted_at,NULL|unique:grades,code,NULL,id,deleted_at,NULL',
                        'phone_number' => 'required|numeric|digits_between:10,11',
                        'admin' => 'required',
                        'name_represent' => 'required|max:255',
                        'email_in_charge' => 'required|email:rfc,dns|max:255',
                    ];
                    return $rule;
                }
            case 'PUT': {
                    $rule = [
                        'name' => 'required|max:255',
                        'phone_number' => 'required|numeric|digits_between:10,11',
                        'admin' => 'required',
                        'name_represent' => 'required|max:255',
                        'email_in_charge' => 'required|email:rfc,dns|max:255',
                    ];
                    return $rule;
                }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            "code.size" => __("response.groupID"),
            "code.unique" => __("response.groupID_unique"),
            "phone_number.numeric" => __("response.phone_number"),
            "phone_number.digits_between" => __("response.phone_number"),
        ];
    }
}
