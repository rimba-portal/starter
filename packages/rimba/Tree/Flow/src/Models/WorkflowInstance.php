<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'workflow_blueprint_id',
    'trackable_id',
    'trackable_type',
    'status',
])]
class WorkflowInstance extends Model
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
        ];
    }

    public function workflowBlueprint(): BelongsTo
    {
        return $this->belongsTo(WorkflowBlueprint::class);
    }

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    public function workflowNodeInstances(): HasMany
    {
        return $this->hasMany(WorkflowNodeInstance::class);
    }

    public function workflowTransitionInstances(): HasMany
    {
        return $this->hasMany(WorkflowTransitionInstance::class);
    }
}
