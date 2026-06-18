<?php

declare(strict_types=1);

namespace Bites\Versioning\Traits;

use Bites\Versioning\Models\Version;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasVersions
{
    public function versions(): MorphMany
    {
        return $this->morphMany(
            Version::class,
            'versionable'
        );
    }

    public function currentVersion(): ?Version
    {
        return $this->versions()
            ->current()
            ->latest('released_at')
            ->first();
    }

    public function latestVersion(): ?Version
    {
        return $this->versions()
            ->latest('id')
            ->first();
    }
}
