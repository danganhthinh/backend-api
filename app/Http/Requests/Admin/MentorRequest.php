<?php

namespace App\Http\Requests\Admin;

use App\Consts;
use App\Repositories\AccountRepository;
use Illuminate\Foundation\Http\FormRequest;

class MentorRequest extends FormRequest
{
    protected $accountRepository;
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;

    }
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
        switch ($this->method()) {
            case 'POST': {
                    $rule = [
                        'full_name' => 'required|max:255',
                        'code' => [
                            'required','digits:4','numeric',
                            function ($attribute, $value, $fail) {
                                $mentorID = Consts::ADMIN_CODE . $value;
                                $data = $this->accountRepository->findByCond([
                                    'student_code' => $mentorID,
                                ]);
                                if(!empty($data)){
                                    return $fail(__('response.mentorID_unique'));
                                }
                            },
                        ],
                        'email' => 'required|email:rfc,dns|max:255',
                        'role_id' => 'required',
                    ];
                    return $rule;
                }
            case 'PUT': {
                    $rule = [
                        'full_name' => 'required|max:255',
                        'email' => 'required|email:rfc,dns|max:255',
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
            "code.digits" => __("response.mentorID"),
            "code.numeric" => __("response.mentorID"),
        ];
    }
}
