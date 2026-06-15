<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Trees\Process\Builders\NodeBuilder;

class WorkflowNode extends Model
{
    protected $fillable = [
        'workflow_id',
        'name',
        'type',
        'role_name',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    // 🔗 Relationships

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function outgoingEdges()
    {
        return $this->hasMany(WorkflowEdge::class, 'from_node_id');
    }

    public function incomingEdges()
    {
        return $this->hasMany(WorkflowEdge::class, 'to_node_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_name', 'name');
    }


public function newEloquentBuilder($query)
{
    return new NodeBuilder($query);
}

    // ✅ Helpers (IMPORTANT)

    public function isStart(): bool
    {
        return $this->type === 'start';
    }

    public function isEnd(): bool
    {
        return $this->type === 'end';
    }

    public function isFirst(): bool
    {
        return $this->incomingEdges()->count() === 0;
    }

    public function isLast(): bool
    {
        return $this->outgoingEdges()->count() === 0;
    }
}