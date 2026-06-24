<?php

declare(strict_types=1);

namespace App\Actions;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class DiscoverBranding
{
    public function execute(): void
    {   
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn() => view('panel.branding')
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_REGISTER_FORM_AFTER,
            fn() => view('panel.branding')
        );
    }
}
