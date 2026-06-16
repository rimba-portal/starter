<?php

declare(strict_types=1);

namespace App\Trees\Organization\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_corp_id',
    'parent_id',
    'name',
    'code',
    'uuid',
    'description',
    'attributes',
])]
class OrgUnit extends Model
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
            'org_corp_id' => 'integer',
            'parent_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(OrgUnit::class);
    }

    public function orgTeams(): HasMany
    {
        return $this->hasMany(OrgTeam::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }
}
