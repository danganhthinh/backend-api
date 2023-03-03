<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $fillable = [
        'name',
        'category_subject_id',
        'sort',
        'status'
    ];
    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function subjectsScore(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubjectScore::class, 'subject_id', 'id');
    }

    public function categorySubject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CategorySubject::class, 'category_subject_id', 'id');
    }
}
