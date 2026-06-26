<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use App\Filament\Staff\Widgets;
use App\Filament\Staff\Widgets\BioDataWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|UnitEnum|null $navigationGroup = 'ToDo';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-target';

    protected static ?string $navigationLabel = 'Target';

    protected static ?int $navigationSort = 13;

    protected static ?string $title = 'Target';

    protected ?string $subheading = 'Target settings and progress overview for your work.';

    public function getColumns(): int|array
    {
        return 4;
    }

    public function getWidgets(): array
    {
        // Only these widgets appear on the Dashboard
        return [
            Widgets\StaffInfo::class,
            Widgets\RolesWidgetMini::class,
            BioDataWidget::class,
        ];
    }
}
