<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AdminDashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode(false)
            ->topbarLivewireComponent('custom-topbar')
            ->brandName('Digital Creativity Tech Shop')
            ->favicon('/logo.png')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                function () {
                    return '<style>
                    .fi-sidebar{display:none!important}
                    .fi-main{margin-left:auto!important;margin-right:auto!important;padding-left:1.5rem!important;padding-right:1.5rem!important;max-width:1400px!important;width:100%!important}
                    .fi-topbar{margin-left:0!important;margin-right:0!important;left:0!important;right:0!important;width:100%!important}
                    [data-fi-page-content-wrapper]{margin-left:0!important;margin-right:0!important;width:100%!important}
                    .fi-page-builder-wrapper{margin-left:0!important;margin-right:0!important;width:100%!important}
                    html[dir=rtl] .fi-main{margin-left:auto!important;margin-right:auto!important;padding-left:1.5rem!important;padding-right:1.5rem!important}
                    html[dir=rtl] [data-fi-page-content-wrapper]{margin-left:0!important;margin-right:0!important}
                    html[dir=rtl] .fi-page-builder-wrapper{margin-left:0!important;margin-right:0!important}
                </style>';
                }
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
