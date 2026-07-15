<?php

declare(strict_types=1);

namespace Bites\FloorPlan\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class DiscoverFloorPlan
{
    public function execute(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Action::make('FloorPlan')
                ->label('Floor Plan')
                ->iconButton()
                ->badge()
                ->icon('bites-location')
                ->url(route('filament.staff.pages.floor-plan'))
                ->toHtml(),
        );
    }
}
