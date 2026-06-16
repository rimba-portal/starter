<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobContracts\Pages;

use App\Http\UI\Admin\Resources\JobContracts\JobContractResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobContract extends CreateRecord
{
    protected static string $resource = JobContractResource::class;
}
