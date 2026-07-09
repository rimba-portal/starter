<?php

namespace Bites\Attributing\Services;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;
use Illuminate\Support\Facades\File;

class GetModelInfo

{
    /**
     * @return Collection<int, class-string<Model>>
     */
    public static function all(): Collection
    {
        return collect()
            ->merge(self::fromApp())
            ->merge(self::fromPackages())
            ->unique()
            ->values();
    }

    /**
     * @return Collection<int, class-string<Model>>
     */
    protected static function fromApp(): Collection
    {
        return self::fromDirectory(
            directory: app_path(),
            basePath: base_path(),
            baseNamespace: ''
        );
    }

    /**
     * @return Collection<int, class-string<Model>>
     */
    protected static function fromPackages(): Collection
    {
        $packageRoot = base_path('packages');

        if (! File::exists($packageRoot)) {
            return collect();
        }

        return collect(File::directories($packageRoot))
            ->flatMap(function (string $vendorDirectory): Collection {
                return collect(File::directories($vendorDirectory))
                    ->flatMap(function (string $packageDirectory): Collection {
                        $srcPath = $packageDirectory . DIRECTORY_SEPARATOR . 'src';

                        if (! File::exists($srcPath)) {
                            return collect();
                        }

                        return self::fromDirectory(
                            directory: $srcPath,
                            basePath: $srcPath,
                            baseNamespace: self::guessPackageNamespace($packageDirectory)
                        );
                    });
            });
    }

    /**
     * @return Collection<int, class-string<Model>>
     */
    protected static function fromDirectory(
        string $directory,
        string $basePath,
        string $baseNamespace
    ): Collection {
        if (! File::exists($directory)) {
            return collect();
        }

        return collect(File::allFiles($directory))
            ->filter(fn (SplFileInfo $file): bool => $file->getExtension() === 'php')
            ->map(fn (SplFileInfo $file): string => self::classFromFile($file, $basePath, $baseNamespace))
            ->map(function (string $class): ?ReflectionClass {
                try {
                    if (! class_exists($class)) {
                        return null;
                    }

                    return new ReflectionClass($class);
                } catch (Exception|Error) {
                    return null;
                }
            })
            ->filter()
            ->filter(fn (ReflectionClass $class): bool => $class->isSubclassOf(Model::class))
            ->filter(fn (ReflectionClass $class): bool => ! $class->isAbstract())
            ->map(fn (ReflectionClass $class): string => $class->getName())
            ->values();
    }

    protected static function classFromFile(
        SplFileInfo $file,
        string $basePath,
        string $baseNamespace
    ): string {
        $relativePath = Str::of($file->getRealPath())
            ->replaceFirst($basePath, '')
            ->replaceLast('.php', '')
            ->trim(DIRECTORY_SEPARATOR)
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->toString();

        if ($baseNamespace !== '') {
            return $baseNamespace . '\\' . $relativePath;
        }

        return Str::of($relativePath)
            ->replaceFirst('app\\', app()->getNamespace())
            ->replaceFirst('App\\', app()->getNamespace())
            ->toString();
    }

    protected static function guessPackageNamespace(string $packageDirectory): string
    {
        /**
         * Example:
         * packages/bites/attributing
         * becomes:
         * Bites\Attributing
         */
        $segments = explode(DIRECTORY_SEPARATOR, $packageDirectory);

        $package = array_pop($segments);
        $vendor = array_pop($segments);

        return Str::studly($vendor) . '\\' . Str::studly($package);
    }

    /**
     * @return class-string<Model>|null
     */
    public static function findByTable(string $tableName): ?string
    {
        return self::all()
            ->first(function (string $modelClass) use ($tableName): bool {
                try {
                    /** @var Model $model */
                    $model = new $modelClass();

                    return $model->getTable() === $tableName;
                } catch (Exception|Error) {
                    return false;
                }
            });
    }
}

