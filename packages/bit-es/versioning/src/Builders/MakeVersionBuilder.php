<?php

declare(strict_types=1);

namespace Bites\Versioning\Builders;

use Bites\Versioning\Enums\VersionIncrementType;
use Bites\Versioning\Models\Version;
use Illuminate\Database\Eloquent\Model;

class MakeVersionBuilder
{
    public $semanticVersionService;

    protected VersionIncrementType $increment =
        VersionIncrementType::Major;

    protected ?string $contentUrl = null;

    protected bool $release = false;

    public function __construct(
        protected Model $model
    ) {}

    public function major(): static
    {
        $this->increment =
            VersionIncrementType::Major;

        return $this;
    }

    public function minor(): static
    {
        $this->increment =
            VersionIncrementType::Minor;

        return $this;
    }

    public function patch(): static
    {
        $this->increment =
            VersionIncrementType::Patch;

        return $this;
    }

    public function url(string $url): static
    {
        $this->contentUrl = $url;

        return $this;
    }

    public function release(): static
    {
        $this->release = true;

        return $this;
    }

    public function execute(): Version
    {
        if (! $latest) {
            return $this->model->versions()->create([
                'version' => '0.0.0',
            ]);
        }

        [$major, $minor, $patch] = match ($this->increment) {

            VersionIncrementType::Major => $this->semanticVersionService->incrementMajor(
                $latest->major
            ),

            VersionIncrementType::Minor => $this->semanticVersionService->incrementMinor(
                $latest->major,
                $latest->minor
            ),

            VersionIncrementType::Patch => $this->semanticVersionService->incrementPatch(
                $latest->major,
                $latest->minor,
                $latest->patch
            ),
        };
    }
}
