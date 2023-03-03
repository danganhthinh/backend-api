<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\GradeRequest;
use App\Repositories\GradeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends BaseController
{
    protected $gradeRepository;
    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }
    public function index()
    {
        $data = $this->gradeRepository->getAll();
        return $this->sendResponse([
            "grade" => $data
        ]);
    }
    public function listBySchool($id)
    {
        $data = $this->gradeRepository->listBySchool($id);
        return $this->sendResponse([
            "grade" => $data
        ]);
    }
    public function searchList(Request $request)
    {
        $searchKey = $request->searchKey;
        $schoolID = $request->school_id;
        $grade = $this->gradeRepository->searchList($searchKey,$schoolID);
        return $this->sendResponse([
            "grade" => $grade
        ]);
    }
    public function infoGradeBySchool($id)
    {
        $grade = $this->gradeRepository->infoGradeBySchool($id);
        return $this->sendResponse([
            "grade" => $grade
        ]);
    }
    public function store(GradeRequest $request)
    {
        $request->validated();
        try {
            $data = $request->all();
            $this->gradeRepository->create($data);
            return $this->sendResponse(__('response.create'));
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function show($id)
    {
        $data = $this->gradeRepository->find($id);
        return $this->sendResponse([
            "grade" => $data
        ]);
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            try {
                DB::beginTransaction();
                $this->gradeRepository->update($data, $id);
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
    public function destroy($id)
    {
        try {
            $this->gradeRepository->destroy($id);
            return $this->sendResponse(__('response.delete'));
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function gradeCanDelete($id){
        return $this->gradeRepository->gradeCanDelete($id);
    }
}
