<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HelpAction extends ActionGroup
{
    public static function get(?string $name = 'help'): static
    {
        return parent::make([
            static::makeInfoAction(),
            static::makeHelpAction(),
        ])
            ->label('')
            ->icon('heroicon-o-question-mark-circle')
            ->iconButton(); // cleaner UX
    }

    protected static function makeInfoAction(): Action
    {
        return Action::make('info')
            ->icon('heroicon-o-book-open')
            ->tooltip('Info')
            ->color('info')
            ->modalHeading('Info')
            ->modalWidth('4xl')
            ->modalContent(fn ($livewire) => static::getHelpView($livewire, 'info'))
            ->slideOver();
    }

    protected static function makeHelpAction(): Action
    {
        return Action::make('help')
            ->icon('heroicon-o-question-mark-circle')
            ->tooltip('Help')
            ->color('warning')
            ->modalHeading('Help')
            ->modalWidth('4xl')
            ->modalContent(fn ($livewire) => static::getHelpView($livewire, 'help'))
            ->slideOver();
    }

    protected static function getHelpView($livewire, string $type)
    {
        [$panel, $resourceName, $recordId, $pageSegment] = static::resolveContext($livewire);

        $segments = array_filter([$resourceName, $recordId]);
        $cleanPath = implode('/', $segments);

        $fileName = $type === 'help'
            ? sprintf('%s-%s', $panel, $pageSegment)
            : $type;

        $paths = static::buildPaths($cleanPath, $resourceName, $fileName);

        foreach ($paths as $path) {
            if (File::exists($path)) {
                return view('filament.help.markdown', [
                    'markdown' => Str::markdown(File::get($path)),
                ]);
            }
        }

        return static::fallbackView($cleanPath, $paths);
    }

    protected static function resolveContext($livewire): array
    {
        $panel = filament()->getCurrentPanel()?->getId();

        $resourceName = null;
        if (method_exists($livewire, 'getResource')) {
            $resourceClass = class_basename($livewire::getResource());

            $resourceName = str($resourceClass)
                ->before('Resource')
                ->plural()
                ->kebab();
        }

        $pageClass = class_basename($livewire);

        $recordId = null;
        if (method_exists($livewire, 'getRecord') && $livewire->getRecord()) {
            $recordId = $livewire->getRecord()->getRouteKey();
        }

        $pageSegment = match (true) {
            str_starts_with($pageClass, 'List') => 'table',
            str_starts_with($pageClass, 'Create') => 'create',
            str_starts_with($pageClass, 'Edit') => 'edit',
            str_starts_with($pageClass, 'View') => 'view',
            default => str($pageClass)->kebab(),
        };

        return [$panel, $resourceName, $recordId, $pageSegment];
    }

    protected static function buildPaths(
        ?string $cleanPath,
        ?string $resourceName,
        string $fileName
    ): array {
        return [
            public_path(sprintf('helpfiles/%s/%s.md', $cleanPath, $fileName)),
            public_path(sprintf('helpfiles/%s/%s.md', $resourceName, $fileName)),
            public_path(sprintf('helpfiles/%s.md', $fileName)),
        ];
    }

    protected static function fallbackView(string $cleanPath, array $paths): Factory|View
    {
        return view('filament.help.markdown', [
            'markdown' => Str::markdown(
                "# No help available\n\n".
                "**Resolved path:** `{$cleanPath}`\n\n".
                "### Expected files:\n\n".
                collect($paths)
                    ->map(fn (string $p): string => sprintf('- `%s`', $p))
                    ->implode("\n")
            ),
        ]);
    }
}
