<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowEdge;
use Repo\App\Process\Models\WorkflowNode;
use Repo\App\Process\Models\WorkflowNodeInstance;

class ProcessNodeTransition
{
    public function execute(WorkflowNodeInstance $nodeInstance): void
    {
        $node = $nodeInstance->node;

        $edges = WorkflowEdge::where('from_node_id', $node->id)->get();

        foreach ($edges as $edge) {

            // ✅ evaluate condition
            $passed = app(ApplyEdgeCondition::class)
                ->execute($edge, $nodeInstance);

            if (! $passed) {
                continue;
            }

            // ✅ create next node instance
            app(CreateNodeInstance::class)->execute([
                'workflow_instance_id' => $nodeInstance->workflow_instance_id,
                'node_id' => $edge->to_node_id,
                'status' => 'active',
            ]);

            // ✅ check if end node
            $nextNode = WorkflowNode::find($edge->to_node_id);

            if ($nextNode->isEnd()) {
                $nodeInstance->workflowInstance
                    ->update(['status' => 'completed']);
            }
        }
    }
}
