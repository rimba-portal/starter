<?php

namespace App\Trees\Process\Support;

use App\Trees\Process\Models\Workflow;

class WorkflowGraph
{
    public function toArray(Workflow $workflow): array
    {
        return [
            'nodes' => $workflow->nodes->map(fn ($n) => [
                'id' => $n->id,
                'name' => $n->name,
                'type' => $n->type,
            ]),
            'edges' => $workflow->edges->map(fn ($e) => [
                'from' => $e->from_node_id,
                'to' => $e->to_node_id,
                'label' => $e->label,
            ]),
        ];
    }

public function toMermaid(Workflow $workflow): string
{
    $lines = ["flowchart TD"];

    foreach ($workflow->nodes as $node) {

        $shape = match ($node->type) {
            'start' => "([{$node->name}])",
            'end'   => "([{$node->name}])",
            'decision' => "{{$node->name}}",
            default => "[{$node->name}]",
        };

        $lines[] = "N{$node->id}{$shape}";
    }

    foreach ($workflow->edges as $edge) {
        $label = $edge->label ? "|{$edge->label}|" : '';
        $lines[] = "N{$edge->from_node_id} -->{$label} N{$edge->to_node_id}";
    }

    return implode("\n", $lines);
}
}