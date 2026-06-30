<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\Workflow;

class CreateWorkflow
{
    public function execute(array $data): Workflow
    {
        return Workflow::create([
            'name' => $data['name'],
            'key' => $data['key'],
        ]);
    }
}
