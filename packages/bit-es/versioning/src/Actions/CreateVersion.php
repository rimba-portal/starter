<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Models\Version;
use Illuminate\Database\Eloquent\Model;

class CreateVersion
{
    public function execute(
        Model $model,
        array $attributes
    ): Version {
        return $model
            ->versions()
            ->create($attributes);
    }
}
