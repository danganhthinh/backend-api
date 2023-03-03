<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IllustrationRequest extends FormRequest
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
        return [
            'avatar' => 'bail|required|mimes:jpeg,png|max:10240|image|mimetypes:image/jpeg,image/png',
        ];
    }
    public function messages()
    {
        return [
            'avatar.max' => __('response.avatar_max'),
            'avatar.mimes' => __('response.wrong_avatar'),
            'avatar.mimetypes' => __('response.wrong_avatar'),
            'avatar.image' => __('response.wrong_avatar'),
        ];
    }
}
