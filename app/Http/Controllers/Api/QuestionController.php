<?php

namespace App\Http\Controllers\Api;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Jobs\handUpdateLevelTotal;
use App\Jobs\handUpdateWrongQuestions;
use App\Models\HistoryRequest;
use App\Repositories\AccountProgressRepository;
use App\Repositories\AccountRepository;
use App\Repositories\FuriganaRepository;
use App\Repositories\HistoryRequestRepository;
use App\Repositories\IllustrationRepository;
use App\Repositories\LevelRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectScoreMonthRepository;
use App\Repositories\SubjectScoreRepository;
use App\Repositories\WrongQuestionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QuestionController extends BaseController
{
    protected QuestionRepository $questionRepository;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepository;
    protected AccountProgressRepository $accountProgressRepository;
    protected SubjectRepository $subjectRepository;
    protected HistoryRequestRepository $historyRequestRepository;
    protected LevelRepository $levelRepository;
    protected WrongQuestionRepository $wrongQuestionRepository;
    protected AccountRepository $accountRepository;
    protected FuriganaRepository $furiganaRepository;
    protected IllustrationRepository $illustrationRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
        $this->subjectRepository = new SubjectRepository();
        $this->historyRequestRepository = new HistoryRequestRepository();
        $this->levelRepository = new LevelRepository();
        $this->wrongQuestionRepository = new WrongQuestionRepository();
        $this->accountRepository = new AccountRepository();
        $this->subjectScoreMonthRepository = new SubjectScoreMonthRepository();
        $this->furiganaRepository = new FuriganaRepository();
        $this->illustrationRepository = new IllustrationRepository();
    }

    public function renderQuestion($subject_id): JsonResponse
    {
        try {
            $account = Auth::user();
            $subject = $this->subjectRepository->find($subject_id);
            if (!$subject) {
                return $this->sendError('Subject id not exist.');
            }

            DB::beginTransaction();
            $account_id = $account->id;

            // Create to Subject Score
            $subject_score = $this->subjectScoreRepository->findByCond([
                'account_id' => $account_id,
                'subject_id' => $subject_id,
            ]);
            if (!$subject_score)
            {
                $level_default = $this->levelRepository->findByCond([
                    'percent_correct' => 0
                ]);
                $subject_score = $this->subjectScoreRepository->create([
                    'account_id' => $account_id,
                    'subject_id' => $subject_id,
                    'level_id' => $level_default->id,
                    'number_correct_answers' => 0,
                    'number_wrong_answer' => 0,
                    'number_training' => 0,
                    'total_questions' => 0
                ]);

                // Create Subject Score Month
                $this->subjectScoreMonthRepository->create([
                    'month' => intval(date('m')),
                    'year' => intval(date('Y')),
                    'subject_score_id' => $subject_score->id,
                    'account_id' => $account_id,
                    'grade_id' => $account->grade_id,
                    'group_id' => $account->group_id,
                    'subject_id' => $subject_id,
                    'level_id' => $level_default->id,
                ]);
            }

            // Random question
            $subject_level = intval($subject_score->level->level);
            $questions_random = $this->questionRepository->randomQuestions($account_id, $subject_id, $subject_level);

            $questions = $questions_random['questions'];

            // Create Training
            $account_progress = $this->accountProgressRepository->create([
                'account_id' => $account_id,
                'grade_id' => $account->grade_id,
                'group_id' => $account->group_id,
                'subject_id' => $subject_id,
                'subject_level' => intval($subject_score->level->level),
                'questions_id' => json_encode(array_column($questions, 'id')),
                'type' => Consts::TYPE_TRAINING,
                'note' => $questions_random['note']
            ]);
            $questions = ($this->unsetTimeStamp(($questions)));
            usort($questions, array($this, "sortUpFuncID"));
            DB::commit();

            return $this->sendResponse([
                'account_progress_id' => $account_progress->id,
                'subject_score_id' => [$subject_score->id],
//                'questions' => $questions,
                'questions' => $this->furiganaRepository->furigana($questions),
            ]);
        }
        catch (\Exception $exception)
        {
            DB::rollback();
            return $this->sendError($exception->getMessage());
        }
    }

    public function randomQuestion(): JsonResponse
    {
        try {
            $account = Auth::user();
            DB::beginTransaction();
            $account_id = $account->id;
            $questions_random = $this->questionRepository->randomByCategory($account);
            $account_progress = $this->accountProgressRepository->create([
                'account_id' => $account_id,
                'grade_id' => $account->grade_id,
                'group_id' => $account->group_id,
                'subjects_id' => json_encode($questions_random['subjects_id']),
                'questions_id' => json_encode($questions_random['questions_id']),
                'subjects_level' => json_encode($questions_random['subjects_level']),
                'type' => Consts::TYPE_TRAINING_RANDOM,
            ]);
            DB::commit();
            return $this->sendResponse([
                'account_progress_id' => $account_progress->id,
                'subject_score_id' => $questions_random['subjects_score_id'],
//                'questions' => $questions_random['questions'],
                'questions' => $this->furiganaRepository->furigana($questions_random['questions']),
           ]);
        }
        catch (\Exception $exception) {
            DB::rollback();
            return $this->sendError($exception->getMessage());
        }
    }

    public function detailQuestion($question_id): JsonResponse
    {
        try
        {
            $question = $this->questionRepository->findByCond([
                'id' => $question_id
            ]);
            if (!$question) {
                return $this->sendResponse('Question not exist');
            }
            return $this->sendResponse([
                'question' => $question
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    public function updateProgress(Request $request): JsonResponse
    {
        $history_request = $this->historyRequestRepository->createHistory(json_encode($request->all()), HistoryRequest::TRAINING_PROGRESS);
        try {
            $check_type_random = false;
            $account_progress_id = intval($request->account_progress_id);
            if (count($request->subject_score_id) === 1) {
                $subject_score_id = intval($request->subject_score_id[0]);
            }
            else {
                $check_type_random = true;
            }
            $wrong_questions = $request->wrong_questions ?? [];
            $correct_questions = $request->correct_questions ?? [];

            $account = Auth::user();
            $account_progress = $this->accountProgressRepository->findByCond([
                'id' => $account_progress_id,
                'account_id' => $account->id
            ]);
            if (!$account_progress){
                $err = 'Account progress not exist.';
                $this->historyRequestRepository->destroy($history_request->id);
                return $this->sendError($err);
            }
            if ($account_progress->status !== Consts::STATUS_DOING){
                $err = 'Account progress done.';
                $this->historyRequestRepository->destroy($history_request->id);
                return $this->sendError($err);
            }

            // Update test result random
            if ($check_type_random == true) {
                return $this->updateProgressRandom($account_progress, $request->subject_score_id, $wrong_questions, $correct_questions, $history_request);
            }

            $subject_score= $this->subjectScoreRepository->findByCond([
                'id' => $subject_score_id,
                'account_id' => $account->id
            ]);
            if (!$subject_score){
                $err = 'Subject score not exist.';
                $this->historyRequestRepository->destroy($history_request->id);
                return $this->sendError($err);
            }

            DB::beginTransaction();

            $level = $this->levelRepository->find($subject_score->level_id);

            // Update Wrong Question
            $update_wrong_job = (new handUpdateWrongQuestions($account->id, $wrong_questions, $correct_questions));
            dispatch($update_wrong_job);

            $update_level_total = (new handUpdateLevelTotal($account->id, $wrong_questions, $correct_questions));
            dispatch($update_level_total);

            // Level calculation
            $total_questions = $this->questionRepository->count([
                'subject_id' => $subject_score->subject_id,
                'status' => Consts::ACTIVE
            ]);
            $answer_correct_all = $this->accountProgressRepository->getAnswerValue($account->id, 'correct_questions',  $subject_score->subject_id);
            $answer_wrong_all = $this->wrongQuestionRepository->getAnswerWrong($account->id, $subject_score->subject_id);

            $answer_correct = array_unique(
                array_merge(
                    $answer_correct_all,
                    $correct_questions
                )
            );
            $answer_wrong = array_unique(
                array_merge(
                    $answer_wrong_all,
                    $wrong_questions
                )
            );
            $question_answered_correctly = $this->correctAnswer($answer_correct, $answer_wrong);
            $questions_correct_active = $this->questionRepository->whereInActive($question_answered_correctly);
            if (count($questions_correct_active) > 0) {
                $this->subjectScoreRepository->update([
                    'corrects_id' => json_encode(array_column($questions_correct_active, 'id'))
                ], $subject_score->id);
            }

            $percent_correct = count($questions_correct_active)*100/$total_questions;
            $level_new = $this->levelRepository->checkLevel($percent_correct);

            // Update to AccountProgress
            $this->accountProgressRepository->update([
                'subject_level' => $level_new->level,
                'wrong_questions' => count($wrong_questions) > 0 ? json_encode($wrong_questions) : null,
                'correct_questions' => count($correct_questions) > 0 ? json_encode($correct_questions) : null,
                'status' => Consts::STATUS_DONE
            ], $account_progress_id);

            // Update to Subject Score
            $this->subjectScoreRepository->update([
                'average_score' => $percent_correct,
                'level_id' => $level_new->id,
                'correct_answer' => count($questions_correct_active),
                'number_correct_answers' => intval($subject_score->number_correct_answers)  + count($correct_questions),
                'number_wrong_answer' => intval($subject_score->number_wrong_answer) + count($wrong_questions),
                'number_training' => intval($subject_score->number_training) + 1,
                'total_questions' => intval($subject_score->total_questions) + count($correct_questions) + count($wrong_questions)
            ], $subject_score->id);

            // Update to Subject Score Month
            $subject_score_month = $this->subjectScoreMonthRepository->findSubjectScoreMonth( $subject_score->id, $account->id);
            if ($subject_score_month) {
                $correct_after = array_unique($answer_correct_all);
                $wrong_after = array_unique($answer_wrong_all);
                $total_correct_after = $this->correctAnswer($correct_after, $wrong_after);

                if ($subject_score_month->corrects_id == null) {
                    $corrects_id = $correct_questions;
                }
                else {
                    $corrects_id = array_unique(array_merge($correct_questions, (array)json_decode($subject_score_month->corrects_id)));
                }

                $this->subjectScoreMonthRepository->update([
                    'average_score' => $percent_correct,
                    'level_id' => $level_new->id,
                    'correct_answer' => intval($subject_score_month->correct_answer) + count(array_diff($correct_questions, $total_correct_after)),
                    'corrects_id' => json_encode((array)$corrects_id),
                    'number_correct_answers' => intval($subject_score_month->number_correct_answers)  + count($correct_questions),
                    'number_wrong_answer' => intval($subject_score_month->number_wrong_answer) + count($wrong_questions),
                    'number_training' => intval($subject_score_month->number_training) + 1,
                    'total_questions' => intval($subject_score_month->total_questions) + count($correct_questions) + count($wrong_questions)
                ], $subject_score_month->id);
            }

            // Update level collection to account
            $level_collection = null;
            if (intval($level_new->level) != 0) {
                $level_collection = (array)json_decode($account->level_collection);
                $check_level_collection = in_array($level_new->id, $level_collection);
                if ($check_level_collection == false) {
                    $level_collection_new = array_unique(array_merge($level_collection, [$level_new->id]));
                    $this->accountRepository->update([
                        'level_collection' => json_encode($level_collection_new)
                    ], $account->id);
                    $level_collection = $level_new;
                }
                else {
                    $level_collection = null;
                }
            }

            $level_drop = null;
            $level_up = null;
            if ($level_new->percent_correct > $level->percent_correct) {
                $level_up = $level_new;
            }
            else if ($level_new->percent_correct < $level->percent_correct) {
                $level_drop = $level_new;
            }
            $illustrator_good = $this->illustrationRepository->getByName('good');
            $illustrator_excellent = $this->illustrationRepository->getByName('EXCELLENT');
            // Update RequestHistory
            $this->historyRequestRepository->updateStatus($history_request->id);
            DB::commit();
            return $this->sendResponse([
                'result' => 'done',
                'level_up' => $level_up,
                'level_drop' => $level_drop,
                'level_collection' => $level_collection,
                'illustrator_good' => $illustrator_good,
                'illustrator_excellent' => $illustrator_excellent
            ]);
        }
        catch (\Exception $exception) {
            DB::rollback();
            $this->historyRequestRepository->updateStatus($history_request->id, HistoryRequest::STATUS_FAIL, $exception->getMessage());
            return $this->sendError($exception->getMessage());
        }

    }

    public function updateProgressRandom($account_progress, $subjects_score_id,  $wrong_questions, $correct_questions, $history_request): JsonResponse
    {
        try {
            $account = Auth::user();
            DB::beginTransaction();
            $all_level_collection = [];
            $all_level_drop = [];
            $all_level_up = [];

            $subjects_level = (array)json_decode($account_progress->subjects_level);

            foreach ($subjects_score_id as $subject_score_id) {
                $subject_score= $this->subjectScoreRepository->findByCond([
                    'id' => $subject_score_id,
                    'account_id' => $account->id
                ]);
                if (!$subject_score){
                    $err = 'Subject score id'.$subject_score_id.' not exist.';
                    $this->historyRequestRepository->destroy($history_request->id);
                    return $this->sendError($err);
                }

                // Level calculation
                $total_questions = $this->questionRepository->count([
                    'subject_id' => $subject_score->subject_id,
                ]);
                $wrong_question = $this->questionRepository->orWhereQuestionsBySubject($wrong_questions, $subject_score->subject_id);
                $correct_question = $this->questionRepository->orWhereQuestionsBySubject($correct_questions, $subject_score->subject_id);

                $answer_correct_all = $this->accountProgressRepository->getAnswerValue($account->id, 'correct_questions', $subject_score->subject_id);
                $answer_wrong_all = $this->wrongQuestionRepository->getAnswerWrong($account->id, $subject_score->subject_id);

                $number_correct_answers = (int)$subject_score->number_correct_answers;
                $number_wrong_answer = (int)$subject_score->number_wrong_answer;
                $question_test_correct = [];
                $check_question_test_correct = null;
                $answers_correct = $answer_correct_all;

                if ($correct_question->count() > 0) {
                    // Update to number_correct_answers
                    ++$number_correct_answers;
                    $check_question_test_correct = true;
                    $question_test_correct[] = $correct_question->toArray()[0]['id'];
                    // Check percent correct
                    $answers_correct = array_unique(
                        array_merge(
                            $answers_correct,
                            [$correct_question->toArray()[0]['id']]
                        )
                    );
                    $answers_wrong = $answer_wrong_all;
                }
                else {
                    if ($wrong_question->count() > 0) {
                        // Update to umber_wrong_answers
                        ++$number_wrong_answer;
                        $check_question_test_correct = false;
                        // Check percent correct
                        $answers_wrong = array_unique(
                            array_merge(
                                $answer_wrong_all,
                                [$wrong_question->toArray()[0]['id']]
                            )
                        );
                    }
                }
                $question_answered_correctly = $this->correctAnswer($answers_correct, $answers_wrong);
                $questions_correct_active = $this->questionRepository->whereInActive($question_answered_correctly);
                if (count($questions_correct_active) > 0) {
                    $this->subjectScoreRepository->update([
                        'corrects_id' => json_encode(array_column($questions_correct_active, 'id'))
                    ], $subject_score->id);
                }

                $percent_correct = count($questions_correct_active)*100/$total_questions;

                $level_new = $this->levelRepository->checkLevel($percent_correct);
                $subjects_level['id'.$subject_score->subject_id] = $level_new->level;

                $this->subjectScoreRepository->update([
                    'average_score' => $percent_correct,
                    'level_id' => $level_new->id,
                    'number_training' => (int)$subject_score->number_training + 1,
                    'correct_answer' => count($questions_correct_active),
                    'number_correct_answers' => $number_correct_answers,
                    'number_wrong_answer' => $number_wrong_answer,
                    'total_questions' => (int)$subject_score->total_questions + 1,
                ], $subject_score->id);

                // Update to Subject Score Month
                $subject_score_month = $this->subjectScoreMonthRepository->findSubjectScoreMonth( $subject_score->id, $account->id);
                if ($subject_score_month) {
                    $correct_after = array_unique($answer_correct_all);
                    $wrong_after = array_unique($answer_wrong_all);
                    $total_correct_after = $this->correctAnswer($correct_after, $wrong_after);
                    $number_correct_answers_month = $subject_score_month->number_correct_answers;
                    $number_wrong_answers_month = $subject_score_month->number_wrong_answer;
                    if ($check_question_test_correct === true) {
                        $number_correct_answers_month = $subject_score_month->number_correct_answers + 1;
                    }
                    else if ($check_question_test_correct === false) {
                        $number_wrong_answers_month =  $subject_score_month->number_wrong_answer + 1;
                    }
                    $this->subjectScoreMonthRepository->update([
                        'average_score' => $percent_correct,
                        'level_id' => $level_new->id,
                        'number_training' => (int)$subject_score_month->number_training + 1,
                        'correct_answer' => (int)$subject_score_month->correct_answer + count(array_diff($question_test_correct, $total_correct_after)),
                        'number_correct_answers' => $number_correct_answers_month,
                        'number_wrong_answer' => $number_wrong_answers_month,
                        'total_questions' => (int)$subject_score_month->total_questions + 1,
                    ], $subject_score_month->id);
                }

                // Update level collection to account
                if ((int)$level_new->level !== 0) {
                    $level_collection = (array)json_decode($account->level_collection);
                    $check_level_collection = in_array($level_new->id, $level_collection);
                    if ($check_level_collection === false) {

                        // Update level collection to account
                        $level_collection_new = array_merge($level_collection, [$level_new->id]);
                        $this->accountRepository->update([
                            'level_collection' => json_encode($level_collection_new, JSON_THROW_ON_ERROR)
                        ], $account->id);

                        // Merge to array level collection
                        $all_level_collection[] = [
                            'subject' => $subject_score->subject->name,
                            'level' => $level_new
                        ];
                    }
                }


                if ($level_new->percent_correct > $subject_score->level->percent_correct) {
                    $all_level_up[] = [
                        'subject' => $subject_score->subject->name,
                        'level' => $level_new
                    ];
                }
                else if ($level_new->percent_correct < $subject_score->level->percent_correct) {
                    $all_level_drop[] = [
                        'subject' => $subject_score->subject->name,
                        'level' => $level_new
                    ];
                }
            }

            // Update to AccountProgress
            $this->accountProgressRepository->update([
                'subjects_level' => json_encode($subjects_level),
                'wrong_questions' => count($wrong_questions) > 0 ? json_encode($wrong_questions) : null,
                'correct_questions' => count($correct_questions) > 0 ? json_encode($correct_questions) : null,
                'status' => Consts::STATUS_DONE
            ], $account_progress->id);

            // Update Wrong Question
            $update_wrong_job = (new handUpdateWrongQuestions($account->id, $wrong_questions, $correct_questions));
            dispatch($update_wrong_job);

            $update_level_total = (new handUpdateLevelTotal($account->id, $wrong_questions, $correct_questions));
            dispatch($update_level_total);
            $this->historyRequestRepository->destroy($history_request->id);

            $illustrator_good = $this->illustrationRepository->getByName('good');
            $illustrator_excellent = $this->illustrationRepository->getByName('EXCELLENT');
            DB::commit();
            return $this->sendResponse([
                'result' => 'done',
                'all_level_up' => $all_level_up,
                'all_level_drop' => $all_level_drop,
                'all_level_collection' => $all_level_collection,
                'illustrator_good' => $illustrator_good,
                'illustrator_excellent' => $illustrator_excellent
            ]);
        }
        catch (\Exception $exception) {
            DB::rollback();
            $this->historyRequestRepository->updateStatus($history_request->id, HistoryRequest::STATUS_FAIL, $exception->getMessage());
            return $this->sendError($exception->getMessage());
        }
    }

    public function countCorrectAnswer($answers_correct, $answers_wrong): int
    {
        return count(array_diff($answers_correct, $answers_wrong));
    }

    public function correctAnswer($answers_correct, $answers_wrong): array
    {
        return array_diff($answers_correct, $answers_wrong);
    }

    public function questionsByProgress($id): JsonResponse
    {
        try {
            $account_progress = $this->accountProgressRepository->find($id);
            if (!$account_progress) {
                return $this->sendError('Account Progress Not Found.');
            }
            $subject_score = [];
            if ($account_progress->type == Consts::TYPE_TRAINING) {
                $subject_score_data = $this->subjectScoreRepository->findByCond([
                    'account_id' =>  $account_progress->account_id,
                    'subject_id' => $account_progress->subject_id
                ]);
                if ($subject_score_data) {
                    $subject_score = [$subject_score_data->id];
                }
            }
            else if ($account_progress->type == Consts::TYPE_TRAINING_RANDOM) {
                $subjects_id = (array)json_decode($account_progress->subjects_id);
                $subject_score = $this->subjectScoreRepository->whereInAndWhere('subject_id',$subjects_id, ['account_id' => $account_progress->account_id]);
                if ($subject_score->count() > 0) {
                    $subject_score = array_column($subject_score->toArray(), 'id');
                }
            }

            $questions_id = (array)json_decode($account_progress->questions_id);
            $questions = $this->questionRepository->whereIn('id', $questions_id)->toArray();
            $questions = ($this->unsetTimeStamp(($questions)));
            usort($questions, array($this, "sortUpFuncID"));
            return $this->sendResponse([
                'account_progress_id' => $account_progress->id,
                'subject_score_id' => $subject_score,
//                'questions' => $questions,
                'questions' => $this->furiganaRepository->furigana($questions),
            ]);
        }
        catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getQuestionFurigana(): JsonResponse
    {
        try {
            $data_question = $this->questionRepository->getQuestionCheckFurigana(3);
            if ($data_question->count() > 0) {
                return $this->sendResponse([
                    'questions' => $data_question->toArray(),
                ]);
            }
            return $this->sendError('null');
        }
        catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function updateFurigana(Request $request): JsonResponse
    {
        try {
            $request =  $request->all();
            $id = (int)$request['id'];
            $content = $request['content'];
            $answer1 = $request['answer1'];
            $answer2 = $request['answer2'];
            $answer3 = $request['answer3'];
            $answer4 = $request['answer4'];
            $this->questionRepository->update([
                'content_furigana' => $content,
                'answer1_furigana' => $answer1,
                'answer2_furigana' => $answer2,
                'answer3_furigana' => $answer3,
                'answer4_furigana' => $answer4,
                'check_furigana' => 1
            ], $id);
            return $this->sendResponse('Update furigana done.');
        }
        catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
