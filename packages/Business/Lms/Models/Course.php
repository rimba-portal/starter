<?php

declare(strict_types=1);

namespace App\Business\Lms\Models;

use App\Trees\Organization\Models\OrgTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'code',
    'title',
    'description',
    'is_active',
    'attributes',
])]
class Course extends Model
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
            'is_active' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function courseModules(): HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function courseGroupAssignments(): HasMany
    {
        return $this->hasMany(CourseGroupAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}
