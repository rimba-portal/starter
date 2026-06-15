<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStep extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'workflow_id',
        'type',
        'name',
        'description',
        'requires_tasks',
        'requires_decision',
        'is_automatic',
        'attributes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'workflow_id' => 'integer',
            'requires_tasks' => 'boolean',
            'requires_decision' => 'boolean',
            'is_automatic' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function fromTransitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class);
    }

    public function toTransitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class);
    }

    public function workflowStepTasks(): HasMany
    {
        return $this->hasMany(WorkflowStepTask::class);
    }

    public function workflowInstanceSteps(): HasMany
    {
        return $this->hasMany(WorkflowInstanceStep::class);
    }

    public function workflowDecisions(): HasMany
    {
        return $this->hasMany(WorkflowDecision::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
