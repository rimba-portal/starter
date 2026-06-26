<?php

declare(strict_types=1);

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'is_enabled',
])]
class IdentityProfile extends Model
{
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

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }
}
