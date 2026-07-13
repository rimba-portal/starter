<?php

declare(strict_types=1);

namespace Bites\Base\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionUnionType;

#[Description('Inspect all PHP classes in app/ and packages/ and overwrite a compact classes.md file')]
#[Signature('bites:class-members')]
class GetClassMembers extends Command
{
    public function handle(): int
    {
        $targetFolders = ['app', 'packages'];
        $outputPath = base_path('classes.md');

        $markdownContent = "# Class Directory Blueprint\n";
        $markdownContent .= '*Generated automatically on '.now()->toDateTimeString()."*\n\n";
        $markdownContent .= "---\n\n";

        $classCount = 0;

        foreach ($targetFolders as $targetFolder) {
            $fullPath = base_path($targetFolder);

            if (! File::isDirectory($fullPath)) {
                $this->warn(sprintf('Directory [%s] not found. Skipping...', $targetFolder));

                continue;
            }

            $this->info(sprintf('Scanning directory: [%s]...', $targetFolder));
            $files = File::allFiles($fullPath);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $className = $this->extractFullyQualifiedClassName($file->getRealPath());

                if (! $className || (! class_exists($className) && ! interface_exists($className) && ! trait_exists($className))) {
                    continue;
                }

                $markdownContent .= $this->generateClassMarkdown($className);
                $classCount++;
            }
        }

        File::put($outputPath, $markdownContent);

        $this->components->info(sprintf('Success! Inspected %d structures. Document refreshed at: [%s]', $classCount, $outputPath));

        return Command::SUCCESS;
    }

    protected function generateClassMarkdown(string $className): string
    {
        try {
            $reflectionClass = new ReflectionClass($className);
            $md = "## 📦 Class: `{$className}`\n\n";

            // 1. Structural Overview Metadata
            $relativePath = str_replace(base_path().'/', '', $reflectionClass->getFileName());
            $md .= "- **Location:** `{$relativePath}` (Line {$reflectionClass->getStartLine()})\n";

            $lineage = [];
            $currentReflection = $reflectionClass;
            while ($parent = $currentReflection->getParentClass()) {
                $lineage[] = '`'.$parent->getName().'`';
                $currentReflection = $parent;
            }

            if ($lineage !== []) {
                $md .= '- **Extends:** '.implode(' ➔ ', $lineage)."\n";
            }

            $interfaces = $reflectionClass->getInterfaceNames();
            if (! empty($interfaces)) {
                $formattedInterfaces = array_map(fn (string $i): string => sprintf('`%s`', $i), $interfaces);
                $md .= '- **Implements:** '.implode(', ', $formattedInterfaces)."\n";
            }

            $traits = array_keys($reflectionClass->getTraits());
            if ($traits !== []) {
                $formattedTraits = array_map(fn (string $t): string => sprintf('`%s`', $t), $traits);
                $md .= '- **Uses Traits:** '.implode(', ', $formattedTraits)."\n";
            }

            // 2. DocBlocks
            $docComment = $reflectionClass->getDocComment();
            if ($docComment) {
                $md .= "\n### 📄 Documentation\n```php\n".trim($docComment)."\n```\n";
            }

            // 3. Extract and filter members
            $properties = $reflectionClass->getProperties();
            $allMethods = $reflectionClass->getMethods();
            $methods = array_values(array_filter($allMethods, fn (\ReflectionMethod $m): bool => $m->getDeclaringClass()->getName() === $className));

            $md .= "\n### ⚙️ Members\n";

            if (empty($properties) && $methods === []) {
                $md .= "*No defined properties or local methods.*\n";
            } else {
                // Single unified 7-column table layout
                $md .= "| Modifier | Type | Property Name | ┃ | Modifier | Method Name | Return Type |\n";
                $md .= "| :--- | :--- | :--- | :---: | :--- | :--- | :--- |\n";

                $maxRows = max(count($properties), count($methods));

                for ($i = 0; $i < $maxRows; $i++) {
                    $pModifiers = ' ';
                    $pType = ' ';
                    $pName = ' ';
                    $mModifiers = ' ';
                    $mName = ' ';
                    $mReturnType = ' ';
                    // Properties side compilation
                    if (isset($properties[$i])) {
                        $prop = $properties[$i];
                        $pModifiers = '`'.(implode(' ', \Reflection::getModifierNames($prop->getModifiers())) ?: 'public').'`';
                        $pType = $prop->hasType() ? '`'.$this->formatType($prop->getType()).'`' : '*mixed*';
                        $pName = sprintf('`$%s`', $prop->getName());
                    }

                    // Methods side compilation
                    if (isset($methods[$i])) {
                        $method = $methods[$i];
                        $mModifiers = '`'.(implode(' ', \Reflection::getModifierNames($method->getModifiers())) ?: 'public').'`';
                        $mReturnType = $method->hasReturnType() ? '`'.$this->formatType($method->getReturnType()).'`' : '*void/mixed*';
                        $mName = sprintf('`%s()`', $method->getName());
                    }

                    // Output clean 7-column markdown string format
                    $md .= "| {$pModifiers} | {$pType} | {$pName} | ┃ | {$mModifiers} | {$mName} | {$mReturnType} |\n";
                }
            }

            return $md."\n---\n\n";

        } catch (\Exception $exception) {
            return "## ❌ Error: `{$className}`\n*Failed to run reflection engine: {$exception->getMessage()}*\n\n---\n\n";
        }
    }

    /**
     * Safely parse basic, union, and intersection types to strings using safe markdown division characters.
     */
    protected function formatType($type): string
    {
        if (! $type) {
            return 'mixed';
        }

        // Use forward slashes instead of pipe characters so the Markdown parser doesn't break columns
        if ($type instanceof ReflectionUnionType) {
            $types = array_map(function (ReflectionIntersectionType|ReflectionNamedType $t) {
                return method_exists($t, 'getName') ? $t->getName() : (string) $t;
            }, $type->getTypes());

            return implode(' / ', $types);
        }

        if ($type instanceof ReflectionIntersectionType) {
            $types = array_map(function (\ReflectionType $t) {
                return method_exists($t, 'getName') ? $t->getName() : (string) $t;
            }, $type->getTypes());

            return implode(' & ', $types);
        }

        if ($type instanceof ReflectionNamedType) {
            return $type->getName();
        }

        return method_exists($type, 'getName') ? $type->getName() : (string) $type;
    }

    protected function extractFullyQualifiedClassName(string $filePath): ?string
    {
        $contents = file_get_contents($filePath);
        $namespace = '';
        $class = '';

        if (preg_match('/namespace\s+([^;]+);/', $contents, $matches)) {
            $namespace = trim($matches[1]);
        }

        if (preg_match('/(?:class|interface|trait)\s+(\w+)/', $contents, $matches)) {
            $class = trim($matches[1]);
        }

        return $class !== '' && $class !== '0' ? ($namespace !== '' && $namespace !== '0' ? $namespace.'\\'.$class : $class) : null;
    }
}
