<?php

declare(strict_types=1);

namespace App\Trees\Process\Services;

use App\Trees\Process\Events\NodeActivated;
use App\Trees\Process\Events\NodeCompleted;
use App\Trees\Process\Models\WorkflowNode;
use App\Trees\Process\Models\WorkflowNodeInstance;
use Illuminate\Support\Collection;

class NodeService
{
    public function isStartNode(WorkflowNode $node): bool
    {
        return $node->type === 'start';
    }

    public function isEndNode(WorkflowNode $node): bool
    {
        return $node->type === 'end';
    }

    public function getNextNodes(WorkflowNode $node): Collection
    {
        return WorkflowNode::whereIn('id', function ($query) use ($node): void {
            $query->select('to_node_id')
                ->from('edges')
                ->where('from_node_id', $node->id);
        })->get();
    }

    public function activateNode(WorkflowNodeInstance $nodeInstance): void
    {
        $nodeInstance->update(['status' => 'active']);

        event(new NodeActivated($nodeInstance));
    }

    public function completeNode(WorkflowNodeInstance $nodeInstance): void
    {
        $nodeInstance->update(['status' => 'completed']);

        event(new NodeCompleted($nodeInstance));
    }
}
