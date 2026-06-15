<?php

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowEdge;
use App\Trees\Process\Models\WorkflowNodeInstance;

class ApplyEdgeCondition
{
    public function execute(WorkflowEdge $edge, WorkflowNodeInstance $nodeInstance): bool
    {
        if (! $edge->condition) {
            return true;
        }

        $condition = $edge->condition;
        $data = $nodeInstance->data ?? [];

        $field = $condition['field'] ?? null;
        $operator = $condition['operator'] ?? '=';
        $value = $condition['value'] ?? null;

        $actual = data_get($data, $field);

        return match ($operator) {
            '='  => $actual == $value,
            '!=' => $actual != $value,
            '>'  => $actual > $value,
            '<'  => $actual < $value,
            default => false,
        };
    }
}