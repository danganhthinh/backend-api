<?php

namespace App\Repositories;

use App\Models\HistoryRequest;

class HistoryRequestRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new HistoryRequest();
    }

    public function createHistory($data, $type, $status = HistoryRequest::STATUS_PENDING , $message = null)
    {
        return $this->create([
            'response_data' => $data,
            'type' => $type,
            'status' => $status,
            'note' => $message
        ]);
    }

    public function updateStatus($id, $status = HistoryRequest::STATUS_DONE, $message= null)
    {
        $data = [
            'status' => $status,
            'note' => $message
        ];
        return $this->update($data, $id);
    }
}