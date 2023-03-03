<?php

namespace App\Repositories;

use App\Models\Level;

class LevelRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected SubjectRepository $subjectRepository;

    public function __construct()
    {
        $this->model = new Level();
        $this->subjectRepository = new SubjectRepository();
    }
    public function checkLevel($percent)
    {
       $percent_correct = $this->model->where('percent_correct', '<=', $percent)->max('percent_correct');
       return $this->findByCond([
           'percent_correct' => $percent_correct,
       ]);
    }

    public function getLevelStamp($level_stamp): array
    {
        $level_stamp = array_values(array_column($level_stamp, null, 'level_id'));
        $stamp = [];
        foreach ($level_stamp as $item) {
            unset($item['level']['percent_correct']);
            unset($item['level']['created_at']);
            unset($item['level']['updated_at']);
            $stamp[] = $item['level'];
        }
        usort($stamp, array($this->subjectRepository, "comparatorFunc"));
        return $stamp;
    }

    public function levelDefault() {
        return $this->model->where('level', 0)->first();
    }
}
