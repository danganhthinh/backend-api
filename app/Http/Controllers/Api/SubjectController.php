<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SubjectController extends BaseController
{
    protected SubjectRepository $subjectRepository;

    public function __construct()
    {
        $this->subjectRepository = new SubjectRepository();
    }

    public function getAll(): JsonResponse
    {
        try {
            $subjects = $this->subjectRepository->getAll(null, null, 'status')->toArray();
            $subjects = $this->unsetTimeStamp($subjects);
            return $this->sendResponse([
                'subjects' => $subjects
            ]);

        } catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getSubjectsTrainingByAccount(Request $request): JsonResponse
    {
        try {
            $account = Auth::user();
            $account_id = $account->id;
            $grade_id = $account->grade_id;
            $group_id = $account->group_id;
            $subjects_data = $this->subjectRepository->getAllSubjectByAccount($account_id, $grade_id, $group_id);
            return $this->sendResponse([
                'subjects' => $subjects_data
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }
}
