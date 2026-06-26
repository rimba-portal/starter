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

    public function format(
        int $major,
        int $minor,
        int $patch,
    ): string {
        return "{$major}.{$minor}.{$patch}";
    }

    public function parse(
        string $version
    ): array {
        [$major, $minor, $patch] =
            explode('.', $version);

        return [
            (int) $major,
            (int) $minor,
            (int) $patch,
        ];
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
}
