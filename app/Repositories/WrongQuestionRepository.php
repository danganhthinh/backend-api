<?php

namespace App\Repositories;

use App\Models\WrongQuestion;

class WrongQuestionRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    public function __construct()
    {
        $this->model = new WrongQuestion();
    }

    public function getAnswerWrong($account_id, $subject_id = null): array
    {
        $answer_wrong =  $this->model
            ->where('account_id',$account_id)
            ->when($subject_id !== null, function ($qr) use ($subject_id) {
                return $qr->whereRelation('question', 'subject_id', '=', $subject_id);
            })
            ->get();

        $data = [];
        if ($answer_wrong->count() > 0)
        {
            foreach ($answer_wrong as $item) {
                if ($item['question_id'] !== null) {
                    $data[] = $item['question_id'];
                }
            }
        }
        return $data;
    }

    public function getWrongQuestionBySubject($account_id, $subject_id, $subject_level)
    {
        return $this->model
            ->where('account_id', $account_id)
            ->whereRelation('question', 'subject_id', '=', $subject_id)
            ->when($subject_level <= 2 ,function ($query){
                return $query->whereRelation('question', 'question_level', '<=', 3);
            })
            ->when($subject_level <= 5 && $subject_level >= 3 ,function ($query){
                return $query->whereRelation('question', 'question_level', '<=', 2);
            })
            ->when($subject_level >= 6 ,function ($query){
                return $query->whereRelation('question', 'question_level', '=', 1);
            })
            ->get();
    }

    public function getWrongQuestionByQuestionLevel($account_id, $subject_id, $question_level)
    {
        return $this->model
            ->where('account_id', $account_id)
            ->whereRelation('question', 'subject_id', '=', $subject_id)
            ->whereRelation('question', 'question_level', '<=' ,$question_level)
            ->get();
    }

    public function updateWrongQuestions($account_id, $wrong_questions, $correct_questions): int
    {
        $data_wrong = $this->findByCond([
            'account_id' => $account_id
        ]);
        if (!$data_wrong) {
            if (count($wrong_questions) > 0){
                foreach ($wrong_questions as $wrong_question) {
                    $this->create([
                        'account_id' => $account_id,
                        'question_id' => $wrong_question
                    ]);
                }
            }
        }
        else {
            // Check array wrong questions
            if (count($wrong_questions) > 0) {
                foreach ($wrong_questions as $wrong_question) {
                    $wrong_check = $this->findByCond([
                        'account_id' => $account_id,
                        'question_id' => $wrong_question
                    ]);
                    if ($wrong_check) {
                        $this->update([
                            'number' => $wrong_check->number + 1
                        ], $wrong_check->id);
                    }
                    else {
                        $this->create([
                            'account_id' => $account_id,
                            'question_id' => $wrong_question
                        ]);
                    }
                }
            }

            // Check array correct questions
            if (count($correct_questions) > 0) {
                foreach ($correct_questions as $correct_question) {
                    $correct_check = $this->findByCond(['account_id' => $account_id, 'question_id' => $correct_question]);
                    if ($correct_check) {
                        $this->destroy($correct_check->id);
                    }
                }
            }
        }
        return 0;
    }
}
