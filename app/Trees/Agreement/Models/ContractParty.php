<?php

declare(strict_types=1);

namespace App\Trees\Agreement\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'contract_id',
    'role',
    'is_signatory',
    'notify_on_expiry',
    'meta',
])]
class ContractParty extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'contract_id' => 'integer',
            'is_signatory' => 'boolean',
            'notify_on_expiry' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function party(): MorphTo
    {
        return $this->morphTo();
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
