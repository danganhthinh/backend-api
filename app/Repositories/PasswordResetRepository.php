<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use Carbon\Carbon;

class PasswordResetRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new PasswordReset();
    }

    public function checkTimeToken($timeToken, $timeExpire = 360): bool
    {
        $timeNow = Carbon::now();
        $timeCheck = $timeNow->diffInSeconds($timeToken);
        if($timeCheck > $timeExpire){
            return false;
        }
        return true;
    }
}
