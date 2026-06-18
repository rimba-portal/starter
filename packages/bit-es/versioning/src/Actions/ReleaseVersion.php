<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Enums\VersionStatus;
use Bites\Versioning\Models\Version;

class ReleaseVersion
{
    public function execute(
        Version $version
    ): Version {

        $version->update([
            'status' => VersionStatus::Released,
            'released_at' => now(),
        ]);

        return $version;
    }
}
