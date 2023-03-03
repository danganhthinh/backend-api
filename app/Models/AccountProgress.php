<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountProgress extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_progress';
    protected $fillable = [
        'account_id',
        'grade_id',
        'group_id',
        'subject_id',
        'subjects_id',
        'video_id',
        'subject_level',
        'subjects_level',
        'questions_id',
        'wrong_questions',
        'correct_questions',
        'correct_video_questions',
        'type',
        'status',
        'note'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    protected function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
