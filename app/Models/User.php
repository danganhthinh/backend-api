<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const RULE_STUDENT = 1;
    const RULE_TEACHER = 2;
    const RULE_ADMIN = 3;

    const INACTIVE = 0;
    const ACTIVE = 1;

    protected $table = 'accounts';
    protected $fillable = [
        'full_name',
        'student_code',
        'password',
        'display_password',
        'role_id',
        'grade_id',
        'group_id',
        'school_id',
        'email',
        'phone_number',
        'birthday',
        'level_collection',
        'check_first_login',
        'number_open_app',
        'usage_time',
        'effective_at',
        'expired_at',
        'status',
        'email_verified_at',
        'remember_token',
        'fcm_token'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = bcrypt($value);
	}
    public function setBirthdayAttribute($value)
	{
		$this->attributes['birthday'] = Carbon::parse($value)->format("Y-m-d");
	}
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function school(): HasMany
    {
        return $this->hasMany(School::class, 'account_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function subjectsScore(): HasMany
    {
        return $this->hasMany(SubjectScore::class, 'account_id', 'id');
    }

    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class,'account_id','id');
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class,'account_id','id');
    }
}
