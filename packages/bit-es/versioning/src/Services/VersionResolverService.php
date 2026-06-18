<?php

declare(strict_types=1);

namespace Bites\Versioning\Services;

class VersionResolverService
{
    public function current($model)
    {
        return $model->versions()
            ->where('status', 'released')
            ->latest()
            ->first();
    }
}
