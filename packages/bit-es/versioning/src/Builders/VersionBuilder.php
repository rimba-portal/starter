<?php

declare(strict_types=1);

namespace Bites\Versioning\Builders;

use Illuminate\Database\Eloquent\Builder;

class VersionBuilder extends Builder
{
    public function released(): static
    {
        return $this->where('status', 'released');
    }

    public function draft(): static
    {
        return $this->where('status', 'draft');
    }

    public function current(): static
    {
        return $this
            ->released()
            ->where(function ($query): void {
                $query
                    ->whereNull('effective_until')
                    ->orWhere(
                        'effective_until',
                        '>',
                        now()
                    );
            });
    }
}
