<?php

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IdentityProfile extends Model
{
    protected $fillable = [
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function personable(): MorphTo
    {
        return $this->morphTo();
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(
            IdentityCredential::class
        );
    }
}