<?php

declare(strict_types=1);

namespace Bites\Versioning\Builders;

use Illuminate\Database\Eloquent\Builder;

class VersionBuilder extends Builder
{
    public function latestVersion(): static
    {
        return $this
            ->orderByDesc('major')
            ->orderByDesc('minor')
            ->orderByDesc('patch');
    }
    public function major(
        int $major
    ): static {
        return $this->where('major', $major);
    }

    public function minor(
        int $major,
        int $minor
    ): static {
        return $this
            ->where('major', $major)
            ->where('minor', $minor);
    }

    public function patch(
        int $major,
        int $minor,
        int $patch
    ): static {
        return $this
            ->where('major', $major)
            ->where('minor', $minor)
            ->where('patch', $patch);
    }
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
            ->effective();
    }
    public function review(): static
    {
        return $this->where('status', 'review');
    }

    public function approved(): static
    {
        return $this->where('status', 'approved');
    }

    public function obsolete(): static
    {
        return $this->where('status', 'obsolete');
    }

    public function archived(): static
    {
        return $this->where('status', 'archived');
    }
    public function effective(): static
    {
        return $this
            ->where('effective_from', '<=', now())
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
