<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SubjectRequest;
use App\Repositories\SubjectRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SubjectController extends BaseController
{
    protected $subjectRepository;
    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }
    public function index()
    {
        $data = $this->subjectRepository->getAll();
        return $this->sendResponse([
            "subject" => $data
        ]);
    }
    public function store(SubjectRequest $request)
    {
        try {
            $data = $request->all();
            $this->subjectRepository->create($data);
            return $this->sendResponse(__('response.create'));
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function show($id)
    {
        $data = $this->subjectRepository->find($id);
        return $this->sendResponse([
            "subject" => $data
        ]);
    }
    public function update(SubjectRequest $request, $id)
    {
        try {
            $data = $request->all();
            try {
                DB::beginTransaction();
                $this->subjectRepository->update($data, $id);
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
            $this->subjectRepository->destroy($id);
            return $this->sendResponse(__('response.delete'));
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
