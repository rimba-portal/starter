<?php

declare(strict_types=1);

namespace Bites\Calendar\Actions;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;

class DiscoverCalendar
{
    public function execute(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIMPLE_PAGE_END,
            fn (): Factory|\Illuminate\Contracts\View\View => view('bites::user-menu-calendar'),
        );
    }
}
