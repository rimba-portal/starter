<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Staff\Pages;

use App\Http\UI\Admin\Resources\Staff\StaffResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;
}
