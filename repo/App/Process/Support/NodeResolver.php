<?php

declare(strict_types=1);

namespace Repo\App\Process\Support;

use App\Trees\Organization\Models\Staff;
use Repo\App\Process\Models\WorkflowNode;

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

            'job_position' => Staff::whereHas('jobPosition', function ($q) use ($assignment): void {
                $q->where('code', $assignment['value']);
            })->get(),

            'staff' => Staff::where('id', $assignment['value'])->get(),

            // ✅ dynamic (powerful)
            'manager_of_requester' => collect([
                optional($subject?->staff?->manager),
            ])->filter(),

            default => collect(),
        };
    }
}
