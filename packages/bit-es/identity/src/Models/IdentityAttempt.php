<?php

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Model;

class IdentityAttempt extends Model
{
    protected $fillable = [
        'identity_profile_id',
        'factor',
        'status',
        'context',
        'attempted_at',
    ];

    protected $casts = [
        'context' => 'array',
        'attempted_at' => 'datetime',
    ];
}