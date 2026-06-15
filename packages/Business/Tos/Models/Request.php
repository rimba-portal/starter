<?php

namespace App\Business\Tos\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Request extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester_id',
        'workflow_instance_id',
        'status',
        'name',
        'description',
        'request_type',
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
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Process\Models\WorkflowInstance::class);
    }
}
