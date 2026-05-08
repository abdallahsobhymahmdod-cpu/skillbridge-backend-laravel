<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password_hash',
    'role',
    'status',
    'email_verified_at',
    'last_login_at',
];

    protected $hidden = [
    'password_hash',
    'remember_token',
];

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
    ];
}

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function reviewsWritten(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function matchesAsUserOne(): HasMany
    {
        return $this->hasMany(Matching::class, 'user1_id');
    }

    public function matchesAsUserTwo(): HasMany
    {
        return $this->hasMany(Matching::class, 'user2_id');
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}