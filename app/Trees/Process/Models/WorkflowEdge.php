<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowTransition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'workflow_id',
        'from_step_id',
        'to_step_id',
        'name',
        'conditions',
        'requires_approval',
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
            'from_step_id' => 'integer',
            'to_step_id' => 'integer',
            'conditions' => 'array',
            'requires_approval' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function fromStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    public function toStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }
}
