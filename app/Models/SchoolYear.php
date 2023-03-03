<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use HasFactory;

    protected $table = 'school_year';
    protected $fillable = [
        'name',
        'time_start',
        'time_end'
    ];

    public function schools(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(School::class, 'school_year_id', 'id');
    }
}
