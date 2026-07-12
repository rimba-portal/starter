<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentType: string
{
    case FilamentPage = 'filament-page';

    case FilamentResource = 'filament-resource';

    case Route = 'route';

    case Url = 'url';

    case Markdown = 'markdown';

    case Document = 'document';

    case Folder = 'folder';

    case Report = 'report';

    case Dashboard = 'dashboard';

    case Api = 'api';

    case File = 'file';

    case Video = 'video';

    case Html = 'html';

    public function label(): string
    {
        return ucwords(str_replace('-', ' ', $this->value));
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [
                $case->value => $case->label(),
            ])
            ->all();
    }
}
