<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Illustration extends Model
{
    use HasFactory;
    protected $table = 'illustrations';
    protected $fillable = [
        'name',
        'image',
        'type',
        'status',
    ];
}
