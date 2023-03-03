<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $fillable = [
        'code',
        'name',
        'phone_number',
        'name_represent',
        'account_id',
        'group_type',
        'email_in_charge',
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class,'group_id','id');
    }
    public function groupType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type');
    }
}
