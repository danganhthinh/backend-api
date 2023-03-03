<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectScoreMonth extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subject_score_month';
    protected $fillable = [
        'month',
        'year',
        'subject_score_id',
        'account_id',
        'grade_id',
        'group_id',
        'subject_id',
        'level_id',
        'average_score',
        'number_training',
        'total_questions',
        'correct_answer',
        'corrects_id',
        'correct_answer_video',
        'number_correct_answers',
        'number_wrong_answer',
        'video_number_learning'
    ];

    public function subject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function level(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
