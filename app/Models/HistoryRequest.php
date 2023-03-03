<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRequest extends Model
{
    use HasFactory;

    // Type request
    const TRAINING_PROGRESS = 'TRAINING_PROGRESS';
    const WRONG_QUESTIONS = 'WRONG_QUESTIONS';
    const LEVEL_TOTAL = 'LEVEL_TOTAL';

    const STATUS_PENDING = 'pending';
    const STATUS_FAIL = 'fail';
    const STATUS_DUPLICATE = 'duplicate';
    const STATUS_DONE = 'done';

    protected $table = 'histories_request';
    protected $fillable = [
        'response_data',
        'type',
        'status',
        'note'
    ];
}
