<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use App\Http\UI\Staff\Widgets;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Override;
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

    #[Override]
    public function getHeaderWidgets(): array
    {
        // Only these widgets appear on the Dashboard
        return [
            // Widgets\StaffInfo::class,
            \Rimba\Tree\Work\Http\UI\Widgets\UnassignedTasksByRoleWidget::class,
            \Rimba\Tree\Work\Http\UI\Widgets\MyPendingTasksWidget::class,
            \Rimba\Tree\Work\Http\UI\Widgets\TaskStatsWidget::class,
            // Widgets\RolesWidgetMini::class,
            // BioDataWidget::class,
        ];
    }
}
