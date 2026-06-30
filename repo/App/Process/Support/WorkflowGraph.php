<?php

declare(strict_types=1);

namespace Repo\App\Process\Support;

use Repo\App\Process\Models\Workflow;

class WorkflowGraph
{
    public function toArray(Workflow $workflow): array
    {
        return [
            'nodes' => $workflow->nodes->map(fn ($n): array => [
                'id' => $n->id,
                'name' => $n->name,
                'type' => $n->type,
            ]),
            'edges' => $workflow->edges->map(fn ($e): array => [
                'from' => $e->from_node_id,
                'to' => $e->to_node_id,
                'label' => $e->label,
            ]),
        ];
    }

    public function toMermaid(Workflow $workflow): string
    {
        $lines = ['flowchart TD'];

        foreach ($workflow->nodes as $node) {

            $shape = match ($node->type) {
                'start' => sprintf('([%s])', $node->name),
                'end' => sprintf('([%s])', $node->name),
                'decision' => sprintf('{%s}', $node->name),
                default => sprintf('[%s]', $node->name),
            };

            $lines[] = sprintf('N%s%s', $node->id, $shape);
        }

        foreach ($workflow->edges as $edge) {
            $label = $edge->label ? sprintf('|%s|', $edge->label) : '';
            $lines[] = sprintf('N%s -->%s N%s', $edge->from_node_id, $label, $edge->to_node_id);
        }

        return implode("\n", $lines);
    }
}
