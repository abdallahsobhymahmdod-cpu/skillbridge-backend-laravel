<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matching extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'user1_id',
        'user2_id',
        'user1_skill_id',
        'user2_skill_id',
        'status',
    ];

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function userOneSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'user1_skill_id');
    }

    public function userTwoSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'user2_skill_id');
    }
}