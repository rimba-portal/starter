<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workflow_instance_id',
    'workflow_transition_id',
    'executed_at',
    'executed_by_id',
])]
class WorkflowTransitionInstance extends Model
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
            'workflow_instance_id' => 'integer',
            'workflow_transition_id' => 'integer',
            'executed_at' => 'timestamp',
            'executed_by_id' => 'integer',
        ];
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function workflowTransition(): BelongsTo
    {
        return $this->belongsTo(WorkflowTransition::class);
    }

    public function executedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
