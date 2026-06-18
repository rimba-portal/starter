<?php

declare(strict_types=1);

namespace Bites\HelpFile;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class HelpFileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bites');

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_ACTIONS_BEFORE,
            fn (): Factory|\Illuminate\Contracts\View\View => view('filament.help.hook')
        );

        $source = __DIR__.'/../resources/files';
        $target = public_path('files');

        File::ensureDirectoryExists($target);

        foreach (File::allFiles($source) as $file) {

            $relativePath = $file->getRelativePathname();
            $destination = $target.DIRECTORY_SEPARATOR.$relativePath;

            File::ensureDirectoryExists(dirname($destination));

            if (! File::exists($destination)) {
                File::copy($file->getPathname(), $destination);
            }
        }
    }

    public function register(): void
    {
        //
    }
}
