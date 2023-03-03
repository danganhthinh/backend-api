<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    protected $fillable = [
        'name',
        'code',
        'school_year_id',
        'phone_number',
        'name_represent',
        'andress_represent',
        'account_id',
        'pic_name',
        'email_in_charge'
    ];

    public function schoolYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function grades(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Grade::class, 'school_id', 'id');
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }
    public function mentor(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mentor::class, 'school_id', 'id');
    }
}
