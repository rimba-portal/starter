<?php

declare(strict_types=1);

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'identity_profile_id',
    'factor_type',
    'value',
    'metadata',
    'is_enabled',
])]
class IdentityCredential extends Model
{
    public function profile(): BelongsTo
    {
        return $this->belongsTo(
            IdentityProfile::class
        );
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_enabled' => 'boolean',
        ];
    }
}
