<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Artifact extends Page
{
    protected static string | UnitEnum | null $navigationGroup = 'Accountables';
    protected static string|BackedEnum|null $navigationIcon = 'rimba-asset-own';
    protected static ?string $navigationLabel = 'Assigned Assets';
    protected static ?int $navigationSort = 22;

    protected static ?string $title = 'Assigned Assets';
    protected ?string $subheading = 'Asset/Equipment/Items issued to you or your support group.';

    protected string $view = 'staff.pages.artifact';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\UserRolesWidget::class,
            // \App\Filament\Hrm\Resources\Staff\Widgets\ShiftMixByOrgUnitTable::class,
        ];
    }

    public static function myclass(): string
    {
        // Late static binding: resolves to the calling class
        return static::class;          // e.g., App\Models\User
    }
}
