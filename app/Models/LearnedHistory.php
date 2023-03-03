<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearnedHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'learned_histories';
    protected $fillable = [
        'account_id',
        'grade_id',
        'group_id'
    ];
}
