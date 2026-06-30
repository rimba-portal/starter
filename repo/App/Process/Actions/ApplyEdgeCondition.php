<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowEdge;
use Repo\App\Process\Models\WorkflowNodeInstance;

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
            '=' => $actual == $value,
            '!=' => $actual != $value,
            '>' => $actual > $value,
            '<' => $actual < $value,
            default => false,
        };
    }
}
