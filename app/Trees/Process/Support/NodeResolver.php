<?php

namespace App\Trees\Process\Support;

use App\Trees\Process\Models\WorkflowNode;
use App\Trees\Organization\Models\Staff;

class NodeResolver
{
    public function resolveStaff(WorkflowNode $node, $subject = null)
    {
        // ✅ Assignment from config
        $assignment = $node->config['assignment'] ?? null;

        if (! $assignment) {
            return collect();
        }

        return match ($assignment['type']) {

            'job_position' => Staff::whereHas('jobPosition', function ($q) use ($assignment) {
                $q->where('code', $assignment['value']);
            })->get(),

            'staff' => Staff::where('id', $assignment['value'])->get(),

            // ✅ dynamic (powerful)
            'manager_of_requester' => collect([
                optional($subject?->staff?->manager)
            ])->filter(),

            default => collect(),
        };
    }
}