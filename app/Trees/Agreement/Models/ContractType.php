<?php

declare(strict_types=1);

namespace App\Trees\Agreement\Models;

use App\Trees\Process\Models\Workflow;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'uuid',
    'name',
    'code',
    'description',
    'template',
    'public_schema',
    'confidential_schema',
    'notify',
    'expiry_notify_days',
    'requires_approval',
    'requires_signature',
    'workflow_id',
    'meta',
])]
class ContractType extends Model
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
            'public_schema' => 'array',
            'confidential_schema' => 'array',
            'notify' => 'array',
            'requires_approval' => 'boolean',
            'requires_signature' => 'boolean',
            'workflow_id' => 'integer',
            'meta' => 'array',
        ];
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
