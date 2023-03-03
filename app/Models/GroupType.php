<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupType extends Model
{
    use HasFactory;
    protected $table = 'group_type';
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class,'group_id','id');
    }
}
