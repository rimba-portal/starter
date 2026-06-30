<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use App\Trees\Organization\Models\OrgTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Role;

#[Fillable([
    'name',
    'owner',
    'active',
])]
class WorkflowBlueprint extends Model
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
            'owner' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function workflowNodes(): HasMany
    {
        return $this->hasMany(WorkflowNode::class);
    }

    public function owner(): BelongsTo
    {
        return $this->BelongsTo(OrgTeam::class);
    }

    public function requesterRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'workflow_blueprint_role');
    }
}
