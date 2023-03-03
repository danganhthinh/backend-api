<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class VideoRequest extends FormRequest
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
                    return [
                        'video' => [
                            'max:153600',
                            function ($attribute, $value, $fail) {
                                if ($value != null && $value != "undefined" && $value != "null") {
                                    $name = str_replace(' ', '', $value->getClientOriginalName());
                                    $media = 'public/' . $name;
                                    if (Storage::exists($media)) {
                                        return $fail(__('response.image_exist'));
                                    }
                                }
                            },
                        ],
                        // 'thumbnail' => function ($attribute, $value, $fail) {
                        //     if ($value != null && $value != "undefined" && $value != "null") {
                        //         $name = str_replace(' ', '', $value->getClientOriginalName());
                        //         $media = 'public/' . $name;
                        //         if (Storage::exists($media)) {
                        //             return $fail(__('response.image_exist'));
                        //         }
                        //     }
                        // },
                        'video_level' => 'required',
                    ];
                }
            case 'PUT': {
                    return [
                        'title' => 'unique:videos,title,NULL,id,deleted_at,NULL',
                        'video' => 'mimes:mp4| max:153600',
                        'video_level' => 'required',
                        'thumbnail' => 'bail|image|mimes:jpeg,png|max:10240|mimetypes:image/jpeg,image/png',
                    ];
                }
        }
    }
    public function messages()
    {
        return [
            'video.max' => __('response.video_max'),
        ];
    }
}
