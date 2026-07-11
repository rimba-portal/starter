<?php

declare(strict_types=1);

namespace App\Enums;

enum VersionStatus: string
{
    case Draft = 'draft';

    case Review = 'review';

    case Released = 'released';

    case Deprecated = 'deprecated';

    case Archived = 'archived';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Review => 'warning',
            self::Released => 'success',
            self::Deprecated => 'danger',
            self::Archived => 'gray',
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