<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStepTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'workflow_step_id',
        'task_template_id',
        'task_list_template_id',
        'is_required',
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
            'workflow_step_id' => 'integer',
            'task_template_id' => 'integer',
            'task_list_template_id' => 'integer',
            'is_required' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    public function taskTemplate(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Todo\Models\TaskTemplate::class);
    }

    public function taskListTemplate(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Todo\Models\TaskListTemplate::class);
    }
}
