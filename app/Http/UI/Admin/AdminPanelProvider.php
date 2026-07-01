<?php

declare(strict_types=1);

namespace App\Http\UI\Admin;

use App\Http\UI\Auth\Pages\Login;
use Filament\Auth\Pages\{Register, EditProfile};
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentIcon;
use Filament\View\PanelsIconAlias;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings; // Import the Action class
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // dd(config('bites.ui.packages'));
        $panel
            ->default()
            ->login(config('bites.ui.auth.login', Login::class))
            ->registration(config('bites.ui.auth.registration', Register::class))
            ->passwordReset(config('bites.ui.auth.password_reset', RequestPasswordReset::class))
            ->profile(config('bites.ui.auth.profile', EditProfile::class))

            ->id(config('bites.ui.panels.admin.0', 'admin'))
            ->path(config('bites.ui.panels.admin.1', 'admin'))
            ->colors(['primary' => config('bites.ui.panels.admin.2', Color::Green)])
            ->brandName(config('bites.ui.panels.admin.3', 'Administration'))
            ->homeUrl(fn(): string => route(config('bites.ui.panels.admin.4', 'filament.admin.pages.dashboard')))

            ->discoverResources(in: app_path('Http/UI/Admin/Resources'), for: 'App\Http\UI\Admin\Resources')
            ->discoverPages(in: app_path('Http/UI/Admin/Pages'), for: 'App\Http\UI\Admin\Pages')
            ->discoverWidgets(in: app_path('Http/UI/Admin/Widgets'), for: 'App\Http\UI\Admin\Widgets');

        $packages = config('bites.ui.packages', []);
        foreach ($packages as $package => $namespace) {
            $panel
                ->discoverResources(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Admin/Resources', $package)),
                    for: $namespace . '\Http\UI\Admin\Resources',
                )
                ->discoverPages(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Admin/Pages', $package)),
                    for: $namespace . '\Http\UI\Admin\Pages',
                )
                ->discoverWidgets(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Admin/Widgets', $package)),
                    for: $namespace . '\Http\UI\Admin\Widgets',
                );
        }

        // dd($panel);
        return $panel
            ->navigationGroups([
                'Classroom',
                'Report Card',
                'Library',
                'Shop',
            ])
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
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
