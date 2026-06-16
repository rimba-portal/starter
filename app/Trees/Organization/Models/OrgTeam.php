<?php

declare(strict_types=1);

namespace App\Trees\Organization\Models;

use App\Trees\Process\Models\Workflow;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_unit_id',
    'name',
    'code',
    'is_active',
    'attributes',
])]
class OrgTeam extends Model
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
            'org_unit_id' => 'integer',
            'is_active' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(Workflow::class);
    }

    public function jobContracts(): HasMany
    {
        return $this->hasMany(JobContract::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }
}
