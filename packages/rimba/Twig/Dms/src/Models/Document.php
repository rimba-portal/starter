<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms\Models;

use App\Trees\Copies\Models\Versionable;
use App\Trees\FloorPlan\Models\Location;
use App\Trees\Organization\Models\OrgTeam;
use App\Trees\Organization\Models\OrgUnit;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable([
    'org_team_id',
    'org_unit_id',
    'location_id',
    'type',
    'title',
    'description',
    'attributes',
])]
class Document extends Model
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
            'org_team_id' => 'integer',
            'org_unit_id' => 'integer',
            'location_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function versionable(): MorphOne
    {
        return $this->morphOne(Versionable::class, 'versionableable');
    }

    public function documentCategoryAssignments(): HasMany
    {
        return $this->hasMany(DocumentCategoryAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
