<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LearningAnalysisRepository extends BaseRepository implements RepositoryInterface
{
    protected AccountRepository $accountRepository;
    protected SubjectRepository $subjectRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->subjectRepository = new SubjectRepository();
    }

    public function learningAnalysis($request)
    {
        $grade_id = $request->grade_id ?? null;
        $group_id = $request->group_id ?? null;
        $accounts = $this->accountRepository->filter([
            'grade_id' => $grade_id,
            'group_id' => $group_id
        ]);
        foreach ($accounts as $account)
        {
            $subjects_level = $this->subjectRepository->getAllSubjectByAccount($account->id, $account->grade_id, $account->group_id);
        }
    }
}