<?php

declare(strict_types=1);

namespace Repo\App\Process\Services;

class ConditionService
{
    public function evaluate(array $condition, array $data): bool
    {
        $field = $condition['field'] ?? null;
        $operator = $condition['operator'] ?? '=';
        $value = $condition['value'] ?? null;

        $actual = data_get($data, $field);

        return match ($operator) {
            '=' => $actual == $value,
            '!=' => $actual != $value,
            '>' => $actual > $value,
            '<' => $actual < $value,
            '>=' => $actual >= $value,
            '<=' => $actual <= $value,
            'in' => in_array($actual, (array) $value),
            default => false,
        };
    }
}
