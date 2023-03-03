<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'questions';
    protected $fillable = [
        'question_type',
        'subject_id',
        'category_question',
        'account_id',
        'title',
        'content',
        'media',
        'answer1',
        'answer2',
        'answer3',
        'answer4',
        'correct_answer',
        'question_level',
        'status',
        'check_furigana',
        'content_furigana',
        'answer1_furigana',
        'answer2_furigana',
        'answer3_furigana',
        'answer4_furigana'
    ];

    public function subject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
