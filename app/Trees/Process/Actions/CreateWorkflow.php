<?php

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\Workflow;

class CreateWorkflow
{
    public function execute(array $data): Workflow
    {
        return Workflow::create([
            'name' => $data['name'],
            'key'  => $data['key'],
        ]);
    }
}