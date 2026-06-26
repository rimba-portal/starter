<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Models\Version;
use Illuminate\Database\Eloquent\Model;
use Bites\Versioning\Services\SemanticVersionService;
use Bites\Versioning\Enums\VersionIncrementType;


class CreateVersion
{
    public function __construct(
        protected SemanticVersionService $semanticVersionService,
    ) {}

    public static function make(Model $model): CreateVersionBuilder
    {
        return new CreateVersionBuilder($model);
    }

    public function execute(
        Model $model,
        VersionIncrementType $increment = VersionIncrementType::Major,
        array $attributes = [],
    ): Version {

        $latest = $model->latestVersion();

        if (! $latest) {

            return $model->versions()->create([
                'version' => '0.0.0',
                'major' => 0,
                'minor' => 0,
                'patch' => 0,
                ...$attributes,
            ]);
        }

        [$major, $minor, $patch] =
            match ($increment) {
                VersionIncrementType::Patch => $this
                    ->semanticVersionService
                    ->incrementPatch(
                        $latest->major,
                        $latest->minor,
                        $latest->patch
                    ),

                VersionIncrementType::Minor => $this
                    ->semanticVersionService
                    ->incrementMinor(
                        $latest->major,
                        $latest->minor
                    ),

                VersionIncrementType::Major => $this
                    ->semanticVersionService
                    ->incrementMajor(
                        $latest->major
                    ),
            };

        return $model->versions()->create([
            'version' => "{$major}.{$minor}.{$patch}",
            'major' => $major,
            'minor' => $minor,
            'patch' => $patch,
            ...$attributes,
        ]);
    }
}
