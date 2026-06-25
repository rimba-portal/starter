<?php

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityCredential extends Model
{
    protected $fillable = [
        'identity_profile_id',
        'factor_type',
        'value',
        'metadata',
        'is_enabled',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_enabled' => 'boolean',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(
            IdentityProfile::class
        );
    }
}