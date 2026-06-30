<?php

namespace Bites\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAuth extends Model
{
    protected $table = 'user_auth';
    protected $fillable = [
        'two_factor_secret', 'two_factor_recovery_codes',
        'two_factor_confirmed_at', 'face_descriptor', 'setup_completed'
    ];
    protected $casts = [
        'two_factor_confirmed_at' => 'datetime',
        'setup_completed' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}