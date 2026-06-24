<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Workflows\Pages;

use App\Http\UI\Admin\Resources\Workflows\WorkflowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflow extends CreateRecord
{
    protected static string $resource = WorkflowResource::class;
}
