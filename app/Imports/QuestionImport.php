<?php

namespace App\Imports;

use App\Consts;
use App\Models\Grade;
use App\Models\Group;
use App\Models\School;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SubjectRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use IntlChar;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class QuestionImport implements ToCollection, WithValidation, WithStartRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected QuestionRepository $questionRepository;
    protected SubjectRepository $subjectRepository;
    protected $question_type;
    protected $cate_question;
    protected $key;
    protected $answer_1;
    protected $answer_2;
    protected $answer_3;
    protected $answer_4;
    protected $key_1;
    protected $key_2;
    protected $key_3;
    protected $key_4;
    public function __construct($questionRepository, $subjectRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->subjectRepository = $subjectRepository;
        $this->question_type = [];
        $this->cate_question = [];
        $this->key = 0;
        $this->answer_1 = [];
        $this->answer_2 = [];
        $this->answer_3 = [];
        $this->answer_4 = [];
        $this->key_1 = 0;
        $this->key_2 = 0;
        $this->key_3 = 0;
        $this->key_4 = 0;
    }

    #[NoReturn] public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $subject_name = $row[1];
            $subject = $this->subjectRepository->findByCond([
                'name' => $subject_name
            ]);
            $level = $row[2];
            if ($row[4] == "テキスト問題") {
                $question_type_id = Consts::TEXT_QUESTION;
            }
            if ($row[4] == "イラスト・画像問題") {
                $question_type_id = Consts::IMAGE_QUESTION;
            }
            if ($row[4] == "2D映像問題") {
                $question_type_id = Consts::QUESTION_2D;
            }
            if ($row[4] == "360映像問題") {
                $question_type_id = Consts::QUESTION_360;
            }
            $content = $row[6];
            $media = null;
            if (!empty($row[5]) && $row[4] != "テキスト問題") {
                $name = str_replace(' ', '', $row[5]);
                $media = env('APP_URL') . "/storage/" . $name;
            }
            $public_type = $row[12];
            if ($public_type == "TRUE") {
                $public = 1;
            } else {
                $public = 0;
            }
            if ($row[3] != "〇×問題") {
                $answer1 = $row[7];
                $answer2 = $row[8];
                $answer3 = $row[9];
                $answer4 = $row[10];
                switch ($row[11]) {
                    case $answer1:
                        $correct_answer = 1;
                        break;
                    case $answer2:
                        $correct_answer = 2;
                        break;
                    case $answer3:
                        $correct_answer = 3;
                        break;
                    case $answer4:
                        $correct_answer = 4;
                        break;
                }
                $category_question = 2;
            }
            if ($row[3] == "〇×問題") {
                if ($row[11] == "TRUE") {
                    $correct_answer = 1;
                } else {
                    $correct_answer = 2;
                }
                $category_question = 1;
                $answer1 = null;
                $answer2 = null;
                $answer3 = null;
                $answer4 = null;
            }

            $data = [
                'question_type' => $question_type_id,
                'category_question' => $category_question,
                'subject_id' => $subject->id,
                'account_id' => Auth::user()->id,
                'title' => $content,
                'content' => $content,
                'answer1' => $answer1,
                'answer2' => $answer2,
                'answer3' => $answer3,
                'answer4' => $answer4,
                'correct_answer' => $correct_answer,
                'question_level' => $level,
                'media' => $media,
                'public' => $public,
            ];
            $this->questionRepository->create($data);
        }
    }
    public function rules(): array
    {
        return [
            // '0' => 'required',
            '1' => [
                'required',
                function ($attribute, $value, $fail) {
                    $subject = $this->subjectRepository->findByCond([
                        'name' => $value
                    ]);
                    if (!$subject) {
                        return $fail(__('excel.wrong.Subject'));
                    }
                },

            ],
            '2' => [
                'required',
                'between:1,3',
            ],
            '3' => [
                function ($attribute, $value, $fail) {
                    $this->cate_question[] = $value;
                    if ($value == null) {
                        return $fail(__('excel.required.required'));
                    }
                    if ($value != "〇×問題" && $value != "４択問題") {
                        return $fail(__('excel.wrong.CategoryQuestion'));
                    }
                },
            ],
            '4' => [
                function ($attribute, $value, $fail) {
                    $this->question_type[] = $value;
                    if ($value == null) {
                        return $fail(__('excel.required.required'));
                    }
                    if ($value != "テキスト問題" && $value != "イラスト・画像問題" && $value != "2D映像問題" && $value != "360映像問題") {
                        return $fail(__('excel.wrong.QuestionType'));
                    }
                },
            ],
            '5' => [
                function ($attribute, $value, $fail) {
                    if ($this->key < count($this->question_type)) {
                        $question_type = $this->question_type[$this->key];
                        $this->key = $this->key + 1;
                        if ($value != null) {
                            $name = str_replace(' ', '', $value);
                            $media = 'public/' . $name;
                            if (!Storage::exists($media)) {
                                return $fail(__('excel.wrong.media'));
                            }
                        }
                        if ($question_type != "テキスト問題" && $value == null) {
                            return $fail(__('excel.required.required'));
                        }
                    }
                },
            ],
            '6' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->key = 0;
                },
            ],
            '7' => [
                function ($attribute, $value, $fail) {
                    $this->key = 0;
                    if ($this->key_1 < count($this->cate_question)) {
                        $cate_question = $this->cate_question[$this->key_1];
                        $this->key_1 = $this->key_1 + 1;
                        $this->answer_1[] = $value;
                        if ($cate_question != "〇×問題" && $value == null) {
                            return $fail(__('excel.required.required'));
                        }
                    }
                }
            ],
            '8' => [
                function ($attribute, $value, $fail) {
                    if ($this->key_2 < count($this->cate_question)) {
                        $cate_question = $this->cate_question[$this->key_2];
                        $this->key_2 = $this->key_2 + 1;
                        $this->answer_2[] = $value;
                        if ($cate_question != "〇×問題" && $value == null) {
                            return $fail(__('excel.required.required'));
                        }
                    }
                }
            ],
            '9' => [
                function ($attribute, $value, $fail) {
                    if ($this->key_3 < count($this->cate_question)) {
                        $cate_question = $this->cate_question[$this->key_3];
                        $this->key_3 = $this->key_3 + 1;
                        $this->answer_3[] = $value;
                        if ($cate_question != "〇×問題" && $value == null) {
                            return $fail(__('excel.required.required'));
                        }
                    }
                }
            ],
            '10' => [
                function ($attribute, $value, $fail) {
                    if ($this->key_4 < count($this->cate_question)) {
                        $cate_question = $this->cate_question[$this->key_4];
                        $this->key_4 = $this->key_4 + 1;
                        $this->answer_4[] = $value;
                        if ($cate_question != "〇×問題" && $value == null) {
                            return $fail(__('excel.required.required'));
                        }
                    }
                }
            ],
            '11' => [
                function ($attribute, $value, $fail) {
                    if ($this->key < count($this->cate_question)) {
                        $cate_question = $this->cate_question[$this->key];
                        $answer1 = $this->answer_1[$this->key];
                        $answer2 = $this->answer_2[$this->key];
                        $answer3 = $this->answer_3[$this->key];
                        $answer4 = $this->answer_4[$this->key];
                        $this->key = $this->key + 1;
                        if ($value == null) {
                            return $fail(__('excel.required.required'));
                        }
                        if ($cate_question == "〇×問題" && gettype($value) != "boolean") {
                            return $fail(__('excel.correct.true/false'));
                        }
                        if ($cate_question != "〇×問題") {
                            if ($value != $answer1 && $value != $answer2 && $value != $answer3 && $value != $answer4) {
                                return $fail(__('excel.correct.4option'));
                            }
                            if(gettype($value) == "boolean"){
                                return $fail(__('excel.correct.4option'));
                            }
                        }
                    }
                }
            ],
            '12' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (str($value) != "1" && str($value) != "") {
                        return $fail(__('excel.wrong.public'));
                    }
                },
            ],
        ];
    }

    public function startRow(): int
    {
        return 3;
    }
    public function customValidationAttributes()
    {
        return [
            '1' => 'B',
            '2' => 'C',
            '3' => 'D',
            '4' => 'E',
            '5' => 'F',
            '6' => 'G',
            '7' => 'H',
            '8' => 'I',
            '9' => 'J',
            '10' => 'K',
            '11' => 'L',
            '12' => 'M',
        ];
    }
    public function customValidationMessages()
    {
        return [
            // '0.required' => __('excel.required.full_name'),
            '1.required' => __('excel.required.required'),
            '2.required' => __('excel.required.required'),
            '3.required' => __('excel.required.required'),
            '4.required' => __('excel.required.required'),
            '6.required' => __('excel.required.required'),
            '7.required_unless' => __('excel.required.required'),
            '8.required_unless' => __('excel.required.required'),
            '9.required_unless' => __('excel.required.required'),
            '10.required_unless' => __('excel.required.required'),
            '11.required' => __('excel.required.required'),
            '12.required' => __('excel.required.required'),
            '2.between' => __('excel.between.Level'),
        ];
    }
}
