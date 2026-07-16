<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Target extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'ToDo';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-target';

    protected static ?string $navigationLabel = 'Target';

    protected static ?int $navigationSort = 13;

    protected static ?string $title = 'Target';

    protected ?string $subheading = 'Target settings and progress overview for your work.';

    protected string $view = 'staff.pages.fix';
}
