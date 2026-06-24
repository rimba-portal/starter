<?php

namespace App\Http\UI\Staff;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\UI\Auth\Pages\Login;
use App\Http\UI\Auth\Pages\ResetPassword;
use Illuminate\View\View;
use Filament\Support\Facades\FilamentIcon;
use Filament\View\PanelsIconAlias;

class StaffPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->login(Login::class)
            ->passwordReset(ResetPassword::class)
            ->id(config('bites.ui.panels.staff.0', 'staff'))
            ->path(config('bites.ui.panels.staff.1', 'staff'))
            ->colors(['primary' => config('bites.ui.panels.staff.2', Color::Blue)])
            ->brandName(config('bites.ui.panels.staff.3', 'ATM Staff Intranet'))
            ->homeUrl(fn(): string => route(config('bites.ui.panels.staff.4', 'filament.staff.pages.dashboard')))


            ->discoverResources(in: app_path('Http/UI/Staff/Resources'), for: 'App\Http\UI\Staff\Resources')
            ->discoverPages(in: app_path('Http/UI/Staff/Pages'), for: 'App\Http\UI\Staff\Pages')
            ->discoverWidgets(in: app_path('Http/UI/Staff/Widgets'), for: 'App\Http\UI\Staff\Widgets');

        $packages = config('bites.ui.packages', []);
        foreach ($packages as $package => $namespace) {
            // dump(base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Resources', $package)), $namespace.'\Http\UI\Staff\Widgets');
            $panel
                ->discoverResources(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Resources', $package)),
                    for: $namespace . '\Http\UI\Staff\Resources',
                )
                ->discoverPages(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Pages', $package)),
                    for: $namespace . '\Http\UI\Staff\Pages',
                )
                ->discoverWidgets(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Widgets', $package)),
                    for: $namespace . '\Http\UI\Staff\Widgets',
                );
        }

        return $panel
            ->navigationGroups([
                'ToDo',
                'Help',
                'Explore',
                'Requests',
                'Improve',
                'Manufacture',
                'Broadcast',
                'Accountables',
            ])
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                // AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
    public function boot(Panel $panel): void
    {
        // Register custom icons
        FilamentIcon::register([
            PanelsIconAlias::PAGES_DASHBOARD_NAVIGATION_ITEM => 'rimba-dashboard',
        ]);
    }
}
