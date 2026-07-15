<?php

declare(strict_types=1);

namespace Repo\App\Process\Filament\Resources\Workflows\Pages;

use Filament\Resources\Pages\CreateRecord;
use Repo\App\Process\Filament\Resources\Workflows\WorkflowResource;

class CreateWorkflow extends CreateRecord
{
    protected static string $resource = WorkflowResource::class;
}
