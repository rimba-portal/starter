<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Models\Version;
use Bites\Versioning\Services\SemanticVersionService;

class GenerateNextVersion
{
    public function __construct(
        protected SemanticVersionService $service
    ) {}

    public function patch(
        Version $version
    ): string {

        [$major, $minor, $patch] =
            $this->service->incrementPatch(
                $version->major,
                $version->minor,
                $version->patch
            );

        return sprintf('%s.%s.%s', $major, $minor, $patch);
    }
}
