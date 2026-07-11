<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuCategory: string
{
    case Enterprise = 'enterprise';

    case People = 'people';

    case Market = 'market';

    case Supply = 'supply';

    case Operate = 'operate';

    case Technology = 'technology';

    case Knowledge = 'knowledge';

    case Source = 'source';

    public function label(): string
    {
        return match ($this) {
            self::Enterprise => 'Enterprise',
            self::People => 'People',
            self::Market => 'Market',
            self::Supply => 'Supply',
            self::Operate => 'Operate',
            self::Technology => 'Technology',
            self::Knowledge => 'Knowledge',
            self::Source => 'Source',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Enterprise => 'heroicon-o-building-office',
            self::People => 'heroicon-o-users',
            self::Market => 'heroicon-o-shopping-bag',
            self::Supply => 'heroicon-o-truck',
            self::Operate => 'heroicon-o-cog-6-tooth',
            self::Technology => 'heroicon-o-cpu-chip',
            self::Knowledge => 'heroicon-o-book-open',
            self::Source => 'heroicon-o-circle-stack',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [
                $case->value => $case->label(),
            ])
            ->all();
    }
}