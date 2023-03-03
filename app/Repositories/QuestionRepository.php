<?php

namespace App\Repositories;

use App\Consts;
use App\Models\AccountProgress;
use App\Models\Question;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;

class QuestionRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected SubjectRepository $subject;
    protected AccountProgressRepository $accountProgressRepository;
    protected WrongQuestionRepository $wrongQuestionRepository;
    protected CategorySubjectRepository $categoryRepository;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected LevelRepository $levelRepository;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepository;
    protected QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->model = new Question();
        $this->accountProgressRepository = new AccountProgressRepository();
        $this->wrongQuestionRepository = new WrongQuestionRepository();
        $this->subject = new SubjectRepository();
        $this->categoryRepository = new CategorySubjectRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->levelRepository = new LevelRepository();
        $this->subjectScoreMonthRepository = new SubjectScoreMonthRepository();
    }

    public function whereInActive($ids)
    {
        return $this->model->whereIn('id', $ids)->where('status', Consts::ACTIVE)->get()->toArray();
    }

    public function getQuestionCheckFurigana($limit)
    {
        return $this->model->where('check_furigana', 0)->limit($limit)->get();
    }

    public function randomQuestions($account_id, $subject_id, $subject_level): array
    {
        $subject_id = intval($subject_id);
        $array_level_question = $this->getArrayLevelQuestions($subject_level);
        $max_level_question = max($array_level_question);
        $wrong_questions = [];
        $correct_questions = [];

        $count_questions_level_max = $this->filter([
            'subject_id' => $subject_id,
            'question_level' => $max_level_question
        ])->count();

        // Check 70% rate
        $check_70 = false;
        $ids_questions_done = $this->accountProgressRepository->getAnswerValue($account_id, 'correct_questions', $subject_id);
        $questions_done_max_level = $this->model
            ->whereIn('id', $ids_questions_done)
            ->where('question_level', $max_level_question)
            ->get()
            ->toArray();
        if ($count_questions_level_max == 0 ) {
            $rate_done = 0;
        }
        else {
            $rate_done = (count($questions_done_max_level)*100)/$count_questions_level_max;
        }
        if ($rate_done >= 70 && $max_level_question < 3) {
            $check_70 = true;
            $max_level_question = $max_level_question + 1;
            $array_level_question[] = $max_level_question;
        }

        $data_wrong_questions = $this->wrongQuestionRepository->getWrongQuestionByQuestionLevel($account_id, $subject_id, $max_level_question);
        if ($data_wrong_questions->count() > 0 ) {
            foreach ($data_wrong_questions as $item) {
                $wrong_questions[] = $item->question_id;
            }
        }

        $training_progress = $this->accountProgressRepository->filter([
            'account_id' => $account_id,
            'subject_id' => $subject_id,
            'type' => Consts::TYPE_TRAINING,
            'status' => Consts::STATUS_DONE
        ]);
        if ($training_progress->count() > 0 ) {
            foreach ($training_progress as $item) {
                if ($item->correct_questions != null) {
                    $correct_questions = array_unique(array_merge($correct_questions, (array)json_decode($item->correct_questions)));
                }
            }
        }
        $correct_questions = array_diff($correct_questions, $wrong_questions);

        // 100:0:0
        if (count($wrong_questions) == 0 && count($correct_questions) == 0) {
            $note = '100:0:0 - unanswered:wrong:correct';
            $questions_return =  $this->model
                ->where('subject_id', $subject_id)
                ->whereIn('question_level', $array_level_question)
                ->where('status', Consts::ACTIVE)
                ->inRandomOrder()
                ->limit(10)
                ->get()
                ->toArray();
            return [
                'questions' => $questions_return,
                'note' => $note
            ];
        }

        elseif (count($wrong_questions) > 0 && count($correct_questions) == 0) {
            $questions_unanswered = $this->model
                ->where('subject_id', $subject_id)
                ->whereNotIn('id', $wrong_questions)
                ->whereIn('question_level', $array_level_question)
                ->where('status', Consts::ACTIVE)
                ->get();

            // 60:40:0
            if ($questions_unanswered->count() > 0) {
                if ($questions_unanswered->count() < 6 ) {
                    $wrong_rate = 10 - $questions_unanswered->count();
                    $unanswered_rate = $questions_unanswered->count();

                    $questions_random_wrong = $this->model
                        ->whereIn('question_level', $array_level_question)
                        ->whereIn('id', $wrong_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit($wrong_rate)
                        ->get()
                        ->toArray();
                }
                else if(count($wrong_questions) < 4) {
                    $unanswered_rate = 10 - count($wrong_questions);
                    $questions_random_wrong = $this->questionWrong($data_wrong_questions->toArray());
                }
                else {
                    $unanswered_rate = 6;
                    $questions_random_wrong = $this->model
                        ->whereIn('question_level', $array_level_question)
                        ->whereIn('id', $wrong_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit(4)
                        ->get()
                        ->toArray();
                }
                $questions_random_unanswered = $this->model
                    ->where('subject_id', $subject_id)
                    ->whereIn('question_level', $array_level_question)
                    ->whereNotIn('id', $wrong_questions)
                    ->where('status', Consts::ACTIVE)
                    ->inRandomOrder()
                    ->limit($unanswered_rate)
                    ->get()
                    ->toArray();

                $note = '60:40:0 - unanswered:wrong:correct';
                $questions_return =  array_merge($questions_random_unanswered, $questions_random_wrong);
                return [
                    'questions' => $questions_return,
                    'note' => $note
                ];
            }

            // 0:100:0
            else {
                $note = '0:100:0 - unanswered:wrong:correct';
                $questions_return =  $this->model
                    ->where('subject_id', $subject_id)
                    ->whereIn('question_level', $array_level_question)
                    ->where('status', Consts::ACTIVE)
                    ->inRandomOrder()
                    ->limit(10)
                    ->get()
                    ->toArray();
                return [
                    'questions' => $questions_return,
                    'note' => $note
                ];
            }
        }

        elseif (count($wrong_questions) == 0 && count($correct_questions) > 0) {
            $questions_unanswered = $this->model
                ->where('subject_id', $subject_id)
                ->whereNotIn('id', $correct_questions)
                ->whereIn('question_level', $array_level_question)
                ->where('status', Consts::ACTIVE)
                ->get();

            // 90:0:10
            if ($questions_unanswered->count() > 0) {
                if ($questions_unanswered->count() < 9 ) {
                    $correct_rate = 10 - $questions_unanswered->count();
                    $unanswered_rate = $questions_unanswered->count();
                    if ($check_70 == true) {
                        $correct_rate = 1;
                        $count_question_level_new = 9 - $questions_unanswered->count();
                    }
                }
                else {
                    $unanswered_rate = 9;
                    $correct_rate = 1;
//                    if ($check_70 == true) {
//                        $unanswered_rate = rand(5,9);
//                        $count_question_level_new = 9 - $unanswered_rate;
//                    }
                }
                $questions_random_unanswered =
                    $this->model
                        ->where('subject_id', $subject_id)
                        ->whereIn('question_level', $array_level_question)
                        ->whereNotIn('id', $correct_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit($unanswered_rate)
                        ->get()
                        ->toArray();

                $questions_random_correct =
                    $this->model
                        ->whereIn('question_level', $array_level_question)
                        ->whereIn('id', $correct_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit($correct_rate)
                        ->get()
                        ->toArray();

//                if ($check_70 == true) {
//                    $level_question_new = count($array_level_question) + 1;
//                    $question_level_new =
//                        $this->model
//                            ->where('subject_id', $subject_id)
//                            ->where('question_level', $level_question_new)
//                            ->whereNotIn('id', $correct_questions)
//                            ->inRandomOrder()
//                            ->limit($count_question_level_new)
//                            ->get()
//                            ->toArray();
//                    $questions_random_unanswered = array_merge($questions_random_unanswered, $question_level_new);
//                }

                $note = '90:0:10 - unanswered:wrong:correct';
                $questions_return =  array_merge($questions_random_unanswered, $questions_random_correct);
                return [
                    'questions' => $questions_return,
                    'note' => $note
                ];
            }

            // 0:0:100
            else {
                $limit_question = 10;
//                if ($check_70 == true) {
//                    $count_question_level_new = 8;
//                    $limit_question = 2;
//                }

                $note = '0:0:100 - unanswered:wrong:correct';
                $questions_return =  $this->model
                    ->where('subject_id', $subject_id)
                    ->whereIn('question_level', $array_level_question)
                    ->where('status', Consts::ACTIVE)
                    ->inRandomOrder()
                    ->limit($limit_question)
                    ->get()
                    ->toArray();

//                if ($check_70 == true) {
//                    $level_question_new = count($array_level_question) + 1;
//                    $question_level_new =
//                        $this->model
//                            ->where('subject_id', $subject_id)
//                            ->where('question_level', $level_question_new)
//                            ->whereNotIn('id', $correct_questions)
//                            ->inRandomOrder()
//                            ->limit($count_question_level_new)
//                            ->get()
//                            ->toArray();
//                    $questions_return = array_merge($questions_return, $question_level_new);
//                    $note = '0:0:100 - unanswered:wrong:correct, level_new (8,2)';
//                }

                return [
                    'questions' => $questions_return,
                    'note' => $note
                ];
            }
        }

        else {
            $questions_unanswered = $this->model
                ->where('subject_id', $subject_id)
                ->whereNotIn('id', $correct_questions)
                ->whereNotIn('id', $wrong_questions)
                ->whereIn('question_level', $array_level_question)
                ->where('status', Consts::ACTIVE)
                ->get();

            // 60:30:10
            if ($questions_unanswered->count() > 0) {
                $wrong_rate = 3;
                $check_wrong = false;
                if (count($wrong_questions) < 3) {
                    $unanswered_rate = 9 - count($wrong_questions);
                    $check_wrong = true;
                }
                else if ($questions_unanswered->count() < 6) {
                    $unanswered_rate = $questions_unanswered->count();
                    $wrong_rate = 9 - $questions_unanswered->count();
                }
                else {
                    $unanswered_rate = 6;
                }

                $questions_random_unanswered =
                    $this->model
                        ->where('subject_id', $subject_id)
                        ->whereIn('question_level', $array_level_question)
                        ->whereNotIn('id', $correct_questions)
                        ->whereNotIn('id', $wrong_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit($unanswered_rate)
                        ->get()
                        ->toArray();

                if ($check_wrong == true) {
                    $questions_random_wrong = $this->questionWrong($data_wrong_questions->toArray());
                }
                else {
                    // Not enough unanswered (raise wrong)
                    if (count($questions_random_unanswered) !== $unanswered_rate) {
                        $wrong_rate += ($unanswered_rate - count($questions_random_unanswered));
                    }
                    $questions_random_wrong = $this->model
                        ->whereIn('question_level', $array_level_question)
                        ->whereIn('id', $wrong_questions)
                        ->inRandomOrder()
                        ->where('status', Consts::ACTIVE)
                        ->limit($wrong_rate)
                        ->get()
                        ->toArray();
                }
                $limit_question_random_correct = 1;
                if (count($questions_random_unanswered)+1+count($questions_random_wrong) !== 10) {
                    $limit_question_random_correct = 10 - count($questions_random_wrong) - count($questions_random_unanswered);
                }
                $questions_random_correct =
                    $this->model
                        ->whereIn('question_level', $array_level_question)
                        ->whereIn('id', $correct_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit($limit_question_random_correct)
                        ->get()
                        ->toArray();

                $note = '60:30:10 - unanswered:wrong:correct';
                $questions_return =  array_merge($questions_random_unanswered, $questions_random_wrong, $questions_random_correct);
                return [
                    'questions' => $questions_return,
                    'note' => $note
                ];
            }

            // 0:80:20
            else {
                $questions_random_wrong = [];
                if (count($correct_questions) > count($wrong_questions)) {
                    if (count($wrong_questions) < 2) {
                        $correct_rate = 9;
                        $questions_random_wrong = $this->questionWrong($data_wrong_questions->toArray());
                    }
                    else {
                        $correct_rate = 2;
                        $questions_random_wrong = $this->model
                            ->whereIn('id', $wrong_questions)
                            ->where('status', Consts::ACTIVE)
                            ->inRandomOrder()
                            ->limit(8)
                            ->get()
                            ->toArray();
                    }
                    if (count($questions_random_wrong) + 2 !== 10) {
                        $correct_rate = 10 - count($questions_random_wrong);
                    }
                    $questions_random_correct = $this->model
                        ->whereIn('id', $correct_questions)
                        ->where('status', Consts::ACTIVE)
                        ->inRandomOrder()
                        ->limit($correct_rate)
                        ->get()
                        ->toArray();
                }
                else {
                    if (count($correct_questions) < 2) {
                        $wrong_rate = 9;
                        $questions_random_correct =
                            $this->model
                                ->whereIn('question_level', $array_level_question)
                                ->whereIn('id', $correct_questions)
                                ->where('status', Consts::ACTIVE)
                                ->get()
                                ->toArray();
                    }
                    else {
                        $wrong_rate = 8;
                        $correct_rate = 2;
                        $questions_random_wrong = $this->model
                            ->whereIn('question_level', $array_level_question)
                            ->whereIn('id', $wrong_questions)
                            ->where('status', Consts::ACTIVE)
                            ->inRandomOrder()
                            ->limit($wrong_rate)
                            ->get()
                            ->toArray();
                        if (count($questions_random_wrong) + $correct_rate !== 10) {
                            $correct_rate = 10 - count($questions_random_wrong);
                        }
                        $questions_random_correct = $this->model
                            ->whereIn('question_level', $array_level_question)
                            ->whereIn('id', $correct_questions)
                            ->where('status', Consts::ACTIVE)
                            ->inRandomOrder()
                            ->limit($correct_rate)
                            ->get()
                            ->toArray();
                    }
                }

                $note = '0:80:20 - unanswered:wrong:correct';
                $questions_return =  array_merge($questions_random_wrong, $questions_random_correct);
                return [
                    'questions' => $questions_return,
                    'note' => $note
                ];
            }
        }
    }


    public function randomByCategory($account)
    {
        $category_subject = $this->categoryRepository->getAll();
        $subjects_id_random = [];
        foreach ($category_subject as $item) {
            if (count($item->subjects) > 1) {
                $random = rand(0,count($item->subjects) - 1);
                $subjects_id_random[] = $item->subjects[$random]->id;
            }
            else {
                $subjects_id_random[] = $item->subjects[0]->id;
            }
        }
        sort($subjects_id_random);
        $questions = [];
        $questions_id = [];
        $subjects_id = [];
        $subjects_level = [];
        $subjects_score_id = [];

        foreach ($subjects_id_random as $id) {
            $subject_score = $this->subjectScoreRepository->findByCond([
                'subject_id' => $id,
                'account_id' => $account->id
            ]);
            if ($subject_score) {
                $subject_level = $subject_score->level->level;
                $question_level = $this->getLevelQuestion($subject_level);
                $level_by_subject = $subject_score->level->level;
            }
            else {
                $question_level = 1;
                $level_by_subject = 0;
            }

            $question_random = $this->model
                ->where('subject_id', $id)
                ->where('question_level', '<=', $question_level)
                ->where('status', Consts::ACTIVE)
                ->inRandomOrder()
                ->first();
            unset($question_random['created_at'], $question_random['updated_at'], $question_random['deleted_at']);
            if ($question_random) {
                $questions[] = $question_random->toArray();
                $questions_id[] = $question_random->id;
                $subjects_id[] = $question_random->subject_id;
                $subjects_level['id'.$id] = $level_by_subject;

                // Update or Create to Subject Score
                $subject_score = $this->subjectScoreRepository->findByCond([
                    'account_id' => $account->id,
                    'subject_id' => $question_random->subject_id,
                ]);
                if (!$subject_score)
                {
                    $level_default = $this->levelRepository->findByCond([
                        'percent_correct' => 0
                    ]);
                    $subject_score = $this->subjectScoreRepository->create([
                        'account_id' => $account->id,
                        'subject_id' => $question_random->subject_id,
                        'level_id' => $level_default->id,
                        'number_correct_answers' => 0,
                        'number_wrong_answer' => 0,
                        'number_training' => 0,
                        'total_questions' => 0
                    ]);

                    // Create Subject Score Month
                    $this->subjectScoreMonthRepository->create([
                        'subject_score_id' => $subject_score->id,
                        'month' => intval(date('m')),
                        'year' => intval(date('Y')),
                        'account_id' => $account->id,
                        'grade_id' => $account->grade_id,
                        'group_id' => $account->group_id,
                        'subject_id' => $question_random->subject_id,
                        'level_id' => $level_default->id,
                    ]);
                }
                $subjects_score_id[] = $subject_score->id;
            }
        }

        return [
            'questions' => $questions,
            'questions_id' => $questions_id,
            'subjects_id' => $subjects_id,
            'subjects_level' => $subjects_level,
            'subjects_score_id' => $subjects_score_id
        ];
    }

    public function orWhereQuestionsBySubject($ids, $subject_id)
    {
        return $this->model->whereIn('id', $ids)->where('subject_id', $subject_id)->get();
    }

    public function getArrayLevelQuestions($subject_level): array
    {
        if (($subject_level >= 6 && $subject_level <= 10) || $subject_level == 0) {
            return [1];
        } else if ($subject_level >= 3 && $subject_level <= 5) {
            return [1,2];
        } else {
            return [1,2,3];
        }
    }

    public function getLevelQuestion($subject_level): int
    {
        if (($subject_level >= 6 && $subject_level <= 10) || $subject_level == 0) {
            return 1;
        } else if ($subject_level >= 3 && $subject_level <= 5) {
            return 2;
        } else {
            return 3;
        }
    }

    public function getQuestionList($id = null)
    {
        return $this->model->where('question_type', $id)->get();
    }

    public function imageQuestion($perPage)
    {
        $question = $this->model->where('question_type', Consts::IMAGE_QUESTION)->orwhere('question_type', Consts::ILLUSTRATED_QUESTION)->paginate($perPage);
        $question = $this->getMentorQuestion($question);
        $question = $this->getSubjectQuestion($question);
        return $question;
    }

    public function textQuestion($perPage)
    {
        $question = $this->model->where('question_type', Consts::TEXT_QUESTION)->paginate($perPage);
        $question = $this->getMentorQuestion($question);
        $question = $this->getSubjectQuestion($question);
        return $question;
    }

    public function Question2D($perPage)
    {
        $question = $this->model->where('question_type', Consts::QUESTION_2D)->paginate($perPage);
        $question = $this->getMentorQuestion($question);
        $question = $this->getSubjectQuestion($question);
        return $question;
    }

    public function Question360($perPage)
    {
        $question = $this->model->where('question_type', Consts::QUESTION_360)->paginate($perPage);
        $question = $this->getMentorQuestion($question);
        $question = $this->getSubjectQuestion($question);
        return $question;
    }
    public function getMentorQuestion($questions)
    {
        foreach ($questions as $question) {
            $question['mentor'] = $this->model->find($question->id)->user;
        }
        return $questions;
    }
    public function getSubjectQuestion($questions){
        foreach ($questions as $question) {
            $subject = $this->subject->find($question->subject_id);
            if (isset($subject->name)) {
                $question['subject_name'] = $subject->name;
            }
        }
        return $questions;
    }
    public function search($searchKey, $subject_id, $question_type)
    {
        if ($question_type == "text") {
            $type = Consts::TEXT_QUESTION;
        } elseif ($question_type == "image") {
            $type = Consts::IMAGE_QUESTION;
        } elseif ($question_type == "2D") {
            $type = Consts::QUESTION_2D;
        } elseif ($question_type == "360") {
            $type = Consts::QUESTION_360;
        }
        $question = $this->model
            ->when(!empty($searchKey), function ($query) use ($searchKey) {
                return $query->where('content', 'like', '%' . $searchKey . '%');
            })
            ->when(!empty($subject_id), function ($query) use ($subject_id) {
                return $query->where('subject_id', $subject_id);
            })
            ->when(!empty($type), function ($query) use ($type) {
                return $query->where('question_type', $type);
            })
            ->paginate(20);
        $question = $this->getMentorQuestion($question);
        $question = $this->getSubjectQuestion($question);
        return $question;
    }

    public function saveMediaImage($image, $subject_id = null, $name): string
    {
        $image_name = str_replace(' ', '', $image->getClientOriginalName());
        $image->storeAs('public/', $image_name);
        return env('APP_URL') . "/storage/" . $image_name;
    }

    public function saveMediaVideo($video, $subject_id = null, $name): string
    {
        // $subject_name = $this->subjectName($subject_id);
        $video_name = str_replace(' ', '', $video->getClientOriginalName());
        $video->storeAs('public/', $video_name);
        // $video_path = 'question/'.$subject_name ."/". $video_name;
        return env('APP_URL') . "/storage/" . $video_name;
    }

    public function changeMediaImage($image, $file_path, $subject_id, $name): string
    {
        $subject_name = $this->subjectName($subject_id);
        $image_url = 'public/' . $this->getMediaUrl($file_path);
        if (Storage::exists($image_url)) {
            Storage::delete($image_url);
        }
        return $this->saveMediaImage($image, $subject_id, $name);
    }

    public function changeMediaVideo($video, $file_path, $subject_id, $name): string
    {
        $subject_name = $this->subjectName($subject_id);
        $video_url = 'public/' . $this->getMediaUrl($file_path);
        if (Storage::exists($video_url)) {
            Storage::delete($video_url);
        }
        return $this->saveMediaVideo($video, $subject_id, $name);
    }

    public function getMediaUrl($file_path): array|string
    {
        $path = env('APP_URL') . "/storage/";
        return str_replace($path, "", $file_path);
    }

    public function subjectName($id)
    {
        $data = $this->subject->find($id);
        return $data->name;
    }

    public function deleteFile($path_media)
    {
        $path = env('APP_URL') . "/storage/";
        $media = 'public/' . str_replace($path, "", $path_media);
        if (Storage::exists($media)) {
            Storage::delete($media);
        }
    }

    public function getFileUrl($subject_id, $name): string
    {
        $subject_name = $this->subjectName($subject_id);
        return 'public/' . $name;
    }

    public function questionWrong($questions_random_wrong)
    {
        $ids_random_wrong = array_column($questions_random_wrong, 'question_id');
        return $this->model
            ->whereIn('id', $ids_random_wrong)
            ->get()
            ->toArray();
    }
}
