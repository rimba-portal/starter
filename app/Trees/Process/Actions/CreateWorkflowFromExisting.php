<?php

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\Workflow;

class CreateWorkflowFromExisting
{
    public function execute(Workflow $source): Workflow
    {
        $new = Workflow::create([
            'name' => $source->name . ' (Copy)',
            'key'  => $source->key . '_' . now()->timestamp,
        ]);

        $nodeMap = [];

        foreach ($source->nodes as $node) {
            $newNode = $node->replicate();
            $newNode->workflow_id = $new->id;
            $newNode->save();

            $nodeMap[$node->id] = $newNode->id;
        }

        foreach ($source->edges as $edge) {
            $new->edges()->create([
                'from_node_id' => $nodeMap[$edge->from_node_id],
                'to_node_id'   => $nodeMap[$edge->to_node_id],
                'label'        => $edge->label,
                'condition'    => $edge->condition,
            ]);
        }

        return $new;
    }
}