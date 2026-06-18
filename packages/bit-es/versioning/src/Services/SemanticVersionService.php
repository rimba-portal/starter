<?php

declare(strict_types=1);

namespace Bites\Versioning\Services;

class SemanticVersionService
{
    public function incrementPatch(
        int $major,
        int $minor,
        int $patch
    ): array {
        return [
            $major,
            $minor,
            $patch + 1,
        ];
    }

    public function incrementMinor(
        int $major,
        int $minor
    ): array {
        return [
            $major,
            $minor + 1,
            0,
        ];
    }

    public function incrementMajor(
        int $major
    ): array {
        return [
            $major + 1,
            0,
            0,
        ];
    }
}
