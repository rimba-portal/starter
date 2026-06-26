<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use App\Filament\Staff\Widgets\RequestWidget;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Workflow extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-w-request';

    protected static ?string $navigationLabel = 'Request for something';

    protected static ?int $navigationSort = 32;

    protected static ?string $title = 'Request for something';

    protected ?string $subheading = 'Request for support, service, item, asset, equipment, etc. through workflow system.';

    protected string $view = 'staff.pages.workflow';

    protected function getHeaderWidgets(): array
    {
        return [
            RequestWidget::class,
        ];
    }
}
