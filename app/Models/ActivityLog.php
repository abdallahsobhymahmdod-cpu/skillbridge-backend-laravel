<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    // ⛔ وقف Laravel عن استخدام updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}