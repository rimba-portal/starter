<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rimba\Tree\Work\Models\WorkPackage;

#[Fillable([
    'workflow_blueprint_id',
    'work_package_id',
    'name',
    'type',
])]
class WorkflowNode extends Model
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
            'workflow_blueprint_id' => 'integer',
            'work_package_id' => 'integer',
        ];
    }

    public function workflowBlueprint(): BelongsTo
    {
        return $this->belongsTo(WorkflowBlueprint::class);
    }

    public function workPackage(): BelongsTo
    {
        return $this->BelongsTo(WorkPackage::class);
    }

    public function toPath(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_node_id');
    }

    public function fromPath(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'to_node_id');
    }
}
