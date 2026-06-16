<?php

declare(strict_types=1);

namespace App\Business\Tos\Models;

use App\Trees\Copies\Models\Versionable;
use App\Trees\Organization\Models\OrgTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable([
    'org_team_id',
    'name',
    'description',
    'attributes',
])]
class Offer extends Model
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
            'attributes' => 'array',
        ];
    }

    public function versionable(): MorphOne
    {
        return $this->morphOne(Versionable::class, 'versionableable');
    }

    public function offerCategoryAssignments(): HasMany
    {
        return $this->hasMany(OfferCategoryAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}
