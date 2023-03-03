<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class QuestionRequest extends FormRequest
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
            'title' => [
                'required',
            ],
            'category_question' => 'required',
            'answer1' => 'required_if:category_question,==,2|max:255',
            'answer2' => 'required_if:category_question,==,2|max:255',
            'answer3' => 'required_if:category_question,==,2|max:255',
            'answer4' => 'required_if:category_question,==,2|max:255',
            'correct_answer' => 'required',
            'question_level' => 'required|between:1,3',
            'subject_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'image.max' => __('response.image_max'),
        ];
    }
}
