<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WrongQuestion extends Model
{
    use HasFactory;

    protected $table = 'wrong_questions';
    protected $fillable = [
        'account_id',
        'question_id',
        'number'
    ];

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class);

}
}
