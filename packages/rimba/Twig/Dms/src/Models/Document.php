<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms\Models;

use App\Trees\Organization\Models\OrgTeam;
use App\Trees\Organization\Models\Staff;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'parent_id', 'doc_number', 'title',
    'site_location', 'status', 'is_controlled', 'team_id', 'author_id',
    'current_version_id', 'security_classification', 'regulatory_impact',
    'risk_assessment_tags', 'retention_period_years', 'effective_date',
    'next_review_date', 'regulatory_hash',
])]
class Document extends Model
{
    use HasVersions;
    use SoftDeletes;
    /**
     * Relationship to the specific structural QMS system hierarchy tier.
     */
    // public function tier(): BelongsTo
    // {
    //     return $this->belongsTo(QmsTier::class, 'qms_tier_id');
    // }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class, 'owner_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'author_id');
    }

    protected function casts(): array
    {
        return [
            'is_controlled' => 'boolean',
            'risk_assessment_tags' => 'array', // Handles flexible JSON tags dynamically
            'effective_date' => 'date',
            'next_review_date' => 'date',
            'retention_period_years' => 'integer',
        ];
    }
}
