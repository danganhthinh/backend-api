<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ClassRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\ClassRepository;

class ClassController extends BaseController
{
    protected $classesRepository;
    public function __construct(ClassRepository $classesRepository)
    {
        $this->classesRepository = $classesRepository;
    }
    public function index()
    {
        $data = $this->classesRepository->getAll();
        return $this->sendResponse([
            "classes" => $data
        ]);
    }
    public function listByGrade($id)
    {
        $data = $this->classesRepository->listByGrade($id);
        return $this->sendResponse([
            "classes" => $data
        ]);
    }
    public function store(ClassRequest $request)
    {
        try {
            $data = $request->all();
            try {
                $this->classesRepository->create($data);
                return $this->sendResponse(__('response.create'));
            } catch (Exception $e) {
                return $this->sendError($e->getMessage());
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function show($id)
    {
        $data = $this->classesRepository->find($id);
        return $this->sendResponse([
            "classes" => $data
        ]);
    }
    public function update(ClassRequest $request, $id)
    {
        try {
            $data = $request->all();
            try {
                DB::beginTransaction();
                $this->classesRepository->update($data, $id);
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
            $this->classesRepository->destroy($id);
            return $this->sendResponse(__('response.delete'));
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
