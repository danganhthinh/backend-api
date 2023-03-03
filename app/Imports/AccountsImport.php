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
use App\Repositories\SchoolRepository;
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
use Illuminate\Support\Facades\Crypt;
use IntlChar;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
class AccountsImport implements ToCollection, WithValidation, WithStartRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected AccountRepository $accountRepository;
    protected SchoolRepository $schoolRepository;
    protected GroupRepository $groupRepository;
    protected GradeRepository $gradeRepository;
    protected $code;
    protected $key;
    protected $school_code;
    protected $school_key;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->schoolRepository = new SchoolRepository();
        $this->groupRepository = new GroupRepository();
        $this->gradeRepository = new GradeRepository();
        $this->code = [];
        $this->key = 0;
        $this->school_code = [];
        $this->school_key = 0;
    }

    #[NoReturn] public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $grade_id = null;
            $group_id = null;
            $full_name = $row[1];
            $code_id = $row[3];
            $school = $this->schoolRepository->findByCond([
                'code' => $row[2],
            ]);
            if (isset($school)) {
                $grade = $this->gradeRepository->findByCond([
                    'code' => $row[4],
                ]);
                $grade_id = $grade->id;
            }
            $group = $this->groupRepository->findByCond([
                'code' => $row[2],
            ]);
            if (isset($group)) {
                $group_id = $group->id;
            }
            $expired_at = Date::excelToDateTimeObject($row[5]);
            $birthday = Date::excelToDateTimeObject($row[6]);
            $row['student_id'] = $code_id;
            $encrypted = Crypt::encrypt(Consts::PASSWORD_DEFAULT);
            $password_hash =  Str::random(15) . $encrypted;
            $data = [
                'full_name' => $full_name,
                'student_code' => $code_id,
                'grade_id' => $grade_id,
                'group_id' => $group_id,
                'password' => Consts::PASSWORD_DEFAULT,
                'display_password' => $password_hash,
                'role_id' => User::RULE_STUDENT,
                'birthday' => $birthday,
                'expired_at' => $expired_at,
            ];
            try {
                $this->accountRepository->create($data);
            } catch (\Exception $exception) {
            }
        }
    }

    public function rules(): array
    {
        return [
            '1' => 'required|max:255',
            '2' => [
                function ($attribute, $value, $fail) {
                    $school_code = School::where("code", $value)->first();
                    $group_code = Group::where("code", $value)->first();
                    if (!empty($school_code)) {
                        $this->code[] = $school_code->code;
                    } elseif (!empty($group_code)) {
                        $this->code[] = $group_code->code;
                    } else {
                        $this->code[] = null;
                    }
                    if ($value == null) {
                        return $fail(__('excel.required.required'));
                    }

                    if (empty($school_code) && empty($group_code)) {
                        return $fail(__('excel.exist.SchoolID/GroupID'));
                    }
                },

            ],
            '3' => [
                'unique:App\Models\User,student_code',
                'distinct',
                function ($attribute, $value, $fail) {
                    if ($this->school_key < count($this->code)) {
                        $check_code  = $this->code[$this->school_key];
                        $this->school_key++;
                        $code = substr($value, 0, 3);
                        $school_code = School::where("code", $code)->first();
                        $group_code = Group::where("code", $code)->first();
                        if ($value == null) {
                            return $fail(__('excel.required.required'));
                        }
                        if ($code != $check_code) {
                            return $fail(__('excel.format.StudentID'));
                        }
                        if (empty($school_code) && empty($group_code)) {
                            return $fail(__('excel.format.StudentID'));
                        }
                        $number = substr($value, 3);
                        if (strlen($number) != 4) {
                            return $fail(__('excel.format.StudentID'));
                        }
                        if (!is_numeric($number)) {
                            return $fail(__('excel.format.StudentID'));
                        }
                    }
                },
            ],
            '4' => [
                function ($attribute, $value, $fail) {
                    if ($this->key < count($this->code)) {
                        $code = $this->code[$this->key];
                        $this->key = $this->key + 1;
                        $school  = $this->schoolRepository->findByCond([
                            'code' => $code
                        ]);
                        if (!empty($school) && empty($value)) {
                            return $fail(__('excel.required.required'));
                        }
                        if ($value != null) {
                            if (strlen($value) != 3) {
                                return $fail(__('excel.format.FacultyID'));
                            }
                            $grade_code = Grade::where("code", $value)->first();
                            if (!empty($school) && !empty($grade_code->school_id)) {
                                if ($grade_code->school_id != $school->id) {
                                    return $fail(__('excel.exist.FacultyID_SchoolID'));
                                }
                            }
                            if (empty($grade_code)) {
                                return $fail(__('excel.exist.FacultyID'));
                            }
                        }
                    }
                },
            ],
            '5' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value)) {
                        if (strlen($value) != 5) {
                            return $fail(__('excel.date.errorDate'));
                        }
                    }
                    $data = Date::excelToDateTimeObject($value)->format("Y-m-d");
                    if ($data < Carbon::now()->format("Y-m-d")) {
                        return $fail(__('excel.date.ExpirationDate'));
                    }
                },
            ],
            '6' => [
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value)) {
                        if (strlen($value) != 5) {
                            return $fail(__('excel.date.errorDate'));
                        }
                    }
                    $data = Date::excelToDateTimeObject($value)->format("Y-m-d");
                    if ($data > Carbon::now()->format("Y-m-d")) {
                        return $fail(__('excel.date.Birthday'));
                    }
                },
            ]
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
        ];
    }

    public function customValidationMessages()
    {
        return [
            '1.required' => __('excel.required.required'),
            '2.required' => __('excel.required.required'),
            '3.required' => __('excel.required.required'),
            '5.required' => __('excel.required.required'),
            '3.unique' => __('excel.unique.studentID'),
            '3.distinct' => __('excel.distinct.code'),
            '5.numeric' => __('excel.date.errorDate'),
            '5.digits' => __('excel.date.errorDate'),
            '6.numeric' => __('excel.date.errorDate'),
            '6.size' => __('excel.date.errorDate'),
        ];
    }
}
