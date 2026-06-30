<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

use App\Trees\Organization\Models\Staff;
use App\Trees\Process\Models\WorkflowInstance;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'requester_id',
    'workflow_instance_id',
    'status',
    'name',
    'description',
    'request_type',
    'attributes',
])]
class Request extends Model
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
            'requester_id' => 'integer',
            'workflow_instance_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function ref(): MorphTo
    {
        return $this->morphTo();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }
}
