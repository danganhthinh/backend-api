<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory;

    protected $table = 'levels';
    protected $fillable = [
        'level',
        'percent_correct',
        'thumbnail',
    ];
}
