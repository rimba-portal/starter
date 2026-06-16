<?php

declare(strict_types=1);

namespace App\Trees\Process\Models;

use App\Trees\Process\Builders\NodeBuilder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

#[Fillable([
    'workflow_id',
    'name',
    'type',
    'role_name',
    'config',
])]
class WorkflowNode extends Model
{
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

    protected function casts(): array
    {
        return [
            'config' => 'array',
        ];
    }
}
