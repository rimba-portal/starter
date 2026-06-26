<?php

declare(strict_types=1);

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'identity_profile_id',
    'factor',
    'status',
    'context',
    'attempted_at',
])]
class IdentityAttempt extends Model
{
    protected function casts(): array
    {
        return [
            'context' => 'array',
            'attempted_at' => 'datetime',
        ];
    }
}
