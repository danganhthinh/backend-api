<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ErrorValidateQuestion;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuestionRequest;
use App\Imports\QuestionImport;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Consts;
use App\Http\Requests\Admin\ExcelRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuestionController extends BaseController
{
    protected $questionRepository;
    protected $subjectRepository;

    public function __construct(QuestionRepository $questionRepository, SubjectRepository $subjectRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->subjectRepository = $subjectRepository;
    }
    public function importData(ExcelRequest $request)
    {
        $fileExcel = $request['file_excel'];
        $fileName = $request->media_names;
        $extension = $fileExcel->getClientOriginalExtension();
        if (!in_array($extension, ['csv', 'xls', 'xlsx'])) {
            $request->validate([
                'excel' => 'required',
            ],
            [
                "excel.required" => __('response.Invalid_file_excel'),
            ]);
        }
        $file = '/public/excel/Error_' . $fileExcel->getClientOriginalName();
        try {
            $accountImport = new QuestionImport($this->questionRepository, $this->subjectRepository);
            Excel::import($accountImport, $fileExcel);
            return $this->sendResponse(__("response.success"));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            Excel::store(new ErrorValidateQuestion($failures), $file);
            if (!empty($fileName)) {
                foreach ($fileName as $filePatch) {
                    $media = 'public/' . $filePatch;
                    if (Storage::exists($media)) {
                        Storage::delete($media);
                    }
                }
            }
            return $this->sendError(asset('storage/excel/Error_' . $fileExcel->getClientOriginalName()));
        }
    }

    public function search(Request $request)
    {
        $searchKey = $request->searchKey;
        $subject_id = $request->subject_id;
        $question_type = $request->type;
        $question = $this->questionRepository->search($searchKey, $subject_id, $question_type);
        if ($request->type == "360") {
            return view("admin.question.360.grid", compact("question"));
        }
        ;
        if ($request->type == "2D") {
            return view("admin.question.2D.grid", compact("question"));
        }
        ;
        if ($request->type == "image") {
            return view("admin.question.image.grid", compact("question"));
        }
        ;
        if ($request->type == "text") {
            return view("admin.question.text.grid", compact("question"));
        }
        ;
    }
    public function imageQuestion()
    {
        $question = $this->questionRepository->imageQuestion(20);
        $subject = $this->subjectRepository->getSubjectBySort();
        return view("admin.question.image.index", compact("question", "subject"));
    }
    public function textQuestion()
    {
        $question = $this->questionRepository->textQuestion(20);
        $subject = $this->subjectRepository->getSubjectBySort();
        return view("admin.question.text.index", compact("question", "subject"));
    }
    public function Question2D()
    {
        $question = $this->questionRepository->Question2D(20);
        $subject = $this->subjectRepository->getSubjectBySort();
        return view("admin.question.2D.index", compact("question", "subject"));
    }
    public function Question360()
    {
        $question = $this->questionRepository->Question360(20);
        $subject = $this->subjectRepository->getSubjectBySort();
        return view("admin.question.360.index", compact("question", "subject"));
    }
    public function fetch(Request $request)
    {
        if ($request->type == Consts::QUESTION_360) {
            $question = $this->questionRepository->Question360(20);
            return view("admin.question.360.grid", compact("question"));
        }
        ;
        if ($request->type == Consts::QUESTION_2D) {
            $question = $this->questionRepository->Question2D(20);
            return view("admin.question.2D.grid", compact("question"));
        }
        ;
        if ($request->type == Consts::IMAGE_QUESTION) {
            $question = $this->questionRepository->imageQuestion(20);
            return view("admin.question.image.grid", compact("question"));
        }
        ;
        if ($request->type == Consts::TEXT_QUESTION) {
            $question = $this->questionRepository->textQuestion(20);
            return view("admin.question.text.grid", compact("question"));
        }
        ;
    }
    public function store(QuestionRequest $request)
    {
        $data = $request->all();
        // $data['title'] =  str_ireplace(array("\r\n",), '<br/>', $request->title);
        $data['title'] = nl2br($request->title);
        $questionType = $request->question_type;
        if ($questionType == Consts::ILLUSTRATED_QUESTION || $questionType == Consts::IMAGE_QUESTION) {
            $image = $request->file('image');
            $image_name = str_replace(' ', '', $image->getClientOriginalName());
            if ($image != null) {
                $request->validate([
                    'image' => [
                        'bail',
                        'image',
                        'mimes:jpeg,png',
                        'max:10240',
                        'mimetypes:image/jpeg,image/png',
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
                ]);
                $data['media'] = $this->questionRepository->saveMediaImage($image, $request->subject_id, $image_name);
            }
        }
        if ($questionType == Consts::QUESTION_360 || $questionType == Consts::QUESTION_2D) {
            $video = $request->file('video');
            $video_name = str_replace(' ', '', $video->getClientOriginalName());
            if ($video != null) {
                $request->validate([
                    'video' => [
                        'mimes:mp4',
                        'max:100000',
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
                ]);
                $data['media'] = $this->questionRepository->saveMediaVideo($video, $request->subject_id, $video_name);
            }
        }
        $data['account_id'] = auth()->user()->id;
        $data['content'] = $data['title'];
        try {
            $this->questionRepository->create($data);
            return $this->sendResponse(__('response.create'));
        } catch (\Exception $exception) {
            if (isset($data['media'])) {
                $this->questionRepository->deleteFile($data['media']);
            }
            return $this->sendError($exception->getMessage());
        }
    }
    public function show($id)
    {
        $data = $this->questionRepository->find($id);
        return $this->sendResponse([
            "question" => $data
        ]);
    }
    public function update(QuestionRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $id = $request->id;
                $data = $request->all();
                $questionType = $request->question_type;
                // $data['title'] =  str_ireplace(array("\r\n",), '<br/>', $request->title);
                $data['title'] = nl2br($request->title);
                $name = time();
                if ($questionType != Consts::TEXT_QUESTION) {
                    if ($request->file('video') || $request->file('image')) {
                        if ($questionType == Consts::QUESTION_360 || $questionType == Consts::QUESTION_2D) {
                            $request->validate([
                                'video' => [
                                    'mimes:mp4',
                                    'max:100000',
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
                            ]);
                            $name_path = env('APP_URL') . "/storage/" . $name . "." . $request->file('video')->getClientOriginalExtension();
                        } else {
                            $request->validate([
                                'image' => [
                                    'bail',
                                    'image',
                                    'mimes:jpeg,png',
                                    'max:10240',
                                    'mimetypes:image/jpeg,image/png',
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
                            ]);
                            $name_path = env('APP_URL') . "/storage/" . $name . "." . $request->file('image')->getClientOriginalExtension();
                        }
                        $data['media'] = $name_path;
                    }
                }
                $data['content'] = $data['title'];
                try {
                    DB::beginTransaction();
                    $data['check_furigana'] = 0;
                    $media = $this->questionRepository->find($id);
                    $this->questionRepository->update($data, $id);
                    if ($questionType == Consts::ILLUSTRATED_QUESTION || $questionType == Consts::IMAGE_QUESTION) {
                        $image = $request->file('image');
                        if ($image != null) {
                            $this->questionRepository->changeMediaImage($image, $media->media, $request->subject_id, $name);
                        }
                    }
                    if ($questionType == Consts::QUESTION_360 || $questionType == Consts::QUESTION_2D) {
                        $video = $request->file('video');
                        if ($video != null) {
                            $this->questionRepository->changeMediaVideo($video, $media->media, $request->subject_id, $name);
                        }
                    }
                    Artisan::call('level:movement');
                    DB::commit();
                    return $this->sendResponse(__('response.update'));
                } catch (Exception $e) {
                    DB::rollback();
                    return $this->sendError($e->getMessage());
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $this->questionRepository->destroy($id);
                return $this->sendResponse(__('response.delete'));
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function storeMultipleFile(Request $request)
    {
        $files = [];
        $exist_file = [];
        if ($request->hasfile('filenames')) {
            foreach ($request->file('filenames') as $file) {
                $name = str_replace(' ', '', $file->getClientOriginalName());
                $media = 'public/' . $name;
                if (!Storage::exists($media)) {
                    $file->storeAs('public/', $name);
                    $files[] = $name;
                } elseif (Storage::exists($media)) {
                    $exist_file[] = $file->getClientOriginalName();
                }
            }
        }
        if (!empty($exist_file)) {
            $exist_file["file_upload"] = $files;
            return $this->sendError($exist_file);
        } else {
            return $this->sendResponse($files);
        }
    }
    public function destroyMultipleFile(Request $request)
    {
        if ($request->names) {
            foreach ($request->names as $name) {
                $media = 'public/' . $name;
                if (Storage::exists($media)) {
                    Storage::delete($media);
                }
            }
        }
    }
}
