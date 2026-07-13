# PHP Files Code Dump
*Generated on: 2026-07-13 16:26:30*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\base`*

---

## File: `database\migrations\0002_01_01_000603_create_api_sync_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\database\migrations\0002_01_01_000603_create_api_sync_tables.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_configs', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique()->index();
            $table->string('source_type');
            $table->json('source_config');
            $table->string('data_path')->nullable();
            $table->json('depends_on')->nullable();
            $table->json('mapping');
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
        Schema::create('api_data', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('api_config_id')->constrained();
            $table->string('fingerprint')->nullable()->index();
            $table->json('payload');
            $table->string('status')->default('pending')->index();
            $table->timestamp('processed_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_data');
        Schema::dropIfExists('api_configs');
    }
};

```

---

## File: `src\Actions\FetchDatabaseData.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Actions\FetchDatabaseData.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

use Bites\Base\Contracts\DataFetcher;
use Illuminate\Support\Facades\DB;

class FetchDatabaseData implements DataFetcher
{
    public function fetch(array $config): array
    {
        return DB::connection($config['connection'])
            ->select($config['query'], $config['bindings'] ?? []);
    }
}

```

---

## File: `src\Actions\FetchRestData.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Actions\FetchRestData.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

use Bites\Base\Contracts\DataFetcher;
use Illuminate\Support\Facades\Http;

class FetchRestData implements DataFetcher
{
    public function fetch(array $config): array
    {
        return Http::withHeaders($config['headers'] ?? [])
            ->get($config['url'], $config['query'] ?? [])
            ->json();
    }
}

```

---

## File: `src\Actions\GeneratePdf.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Actions\GeneratePdf.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

class GeneratePdf
{
    public function execute(string $view, array $data = []): string
    {
        // Example: using barryvdh/laravel-dompdf
        $pdf = app('dompdf.wrapper')
            ->loadView($view, $data);

        $fileName = storage_path('app/tmp/'.uniqid().'.pdf');

        $pdf->save($fileName);

        return $fileName;
    }
}

```

---

## File: `src\Actions\GenerateUniqueCode.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Actions\GenerateUniqueCode.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

class GenerateUniqueCode
{
    /**
     * Executes the action to generate a short alphanumeric code based on a phrase.
     *
     * @param  string  $phrase  The text to turn into a code.
     * @param  int  $length  How long the code should be (defaults to 3).
     * @param  callable|null  $isDuplicateCallback  A function that checks if the code is already taken.
     */
    public function execute(string $phrase, int $length = 3, ?callable $isDuplicateCallback = null): string
    {
        // 1. Clean the phrase (lowercase and remove spaces)
        $cleanPhrase = str_replace(' ', '', strtolower($phrase));

        // 2. Hash the phrase using MD5
        $hash = md5($cleanPhrase);

        // Alphanumeric alphabet: 0-9 and A-Z (36 options)
        $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($alphabet);

        // 3. Convert a segment of the hash into numbers.
        // We take a larger segment (up to 12 hex chars) to support longer code lengths comfortably.
        $hexSegment = substr($hash, 0, 12);
        $number = hexdec($hexSegment);

        // 4. Shrink the number down to the desired character length
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $remainder = $number % $base;
            $code .= $alphabet[$remainder];
            $number = intdiv($number, $base);
        }

        $finalCode = strrev($code);

        // 5. Database safety loop using the dynamic callback function
        $counter = 1;
        while ($isDuplicateCallback && $isDuplicateCallback($finalCode)) {
            $newHash = md5($cleanPhrase.$counter);
            $hexSegment = substr($newHash, 0, 12);
            $number = hexdec($hexSegment);

            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $remainder = $number % $base;
                $code .= $alphabet[$remainder];
                $number = intdiv($number, $base);
            }

            $finalCode = strrev($code);
            $counter++;
        }

        return $finalCode;
    }
}

```

---

## File: `src\Actions\PutFingerPrint.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Actions\PutFingerPrint.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

class PutFingerPrint
{
    public static function make(array $payload): string
    {
        return sha1(json_encode($payload));
    }
}

```

---

## File: `src\Actions\SendNotification.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Actions\SendNotification.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendNotification
{
    public function execute(User $user, string $message, array $context = []): void
    {
        // Placeholder (plug into Mail / DB / Broadcast)
        Log::info('Notification', [
            'user_id' => $user->id,
            'message' => $message,
            'context' => $context,
        ]);
    }
}

```

---

## File: `src\BaseServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\BaseServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class BaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->registerCommandsFromDirectory();
        }
    }

    /**
     * Dynamically discover and boot all commands inside the package directory.
     */
    protected function registerCommandsFromDirectory()
    {
        $commandDir = __DIR__.'/Console/Commands';
        // Ensure the folder exists before scanning
        if (! is_dir($commandDir)) {
            return;
        }

        $commands = [];

        // Loop through all PHP files in your package's command directory
        foreach (glob($commandDir.'/*.php') as $file) {
            $className = basename($file, '.php');

            // Reconstruct the exact fully qualified namespace
            $class = 'Bites\\Base\\Console\\Commands\\'.$className;

            // Check that the class exists and actually extends Laravel's base Command
            if (class_exists($class) && is_subclass_of($class, Command::class)) {
                // Ensure it is not an abstract class
                $reflection = new ReflectionClass($class);
                if (! $reflection->isAbstract()) {
                    $commands[] = $class;
                }
            }
        }

        // Register all found commands into the application framework
        if ($commands !== []) {
            $this->commands($commands);
        }
    }
}

```

---

## File: `src\Console\Commands\ApiFetchCommand.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Console\Commands\ApiFetchCommand.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Console\Commands;

use Bites\Base\Models\ApiConfig;
use Bites\Base\Services\FetchService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Fetch data using API pipeline configs')]
#[Signature('bites:fetch {identifier? : ID or name of the config}')]
class ApiFetchCommand extends Command
{
    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $configs = match (true) {
            is_numeric($identifier) => ApiConfig::where('id', $identifier)->get(),

            is_string($identifier) => ApiConfig::where('name', $identifier)->get(),

            default => ApiConfig::where('active', true)->get(),
        };

        if ($configs->isEmpty()) {
            $this->error('No API config found.');

            return self::FAILURE;
        }

        foreach ($configs as $config) {
            app(FetchService::class)->fetch($config);
        }

        $this->info('API fetch complete');

        return self::SUCCESS;
    }
}

```

---

## File: `src\Console\Commands\ApiSampleCommand.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Console\Commands\ApiSampleCommand.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Console\Commands;

use Bites\Base\Models\ApiConfig;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Yaml\Yaml;

#[Description('Sample top 10 raw rows from API config (JSON/YAML)')]
#[Signature('bites:sample 
                            {identifier? : ID or name of the config}
                            {--json : Output as JSON}
                            {--yaml : Output as YAML}')]
class ApiSampleCommand extends Command
{
    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $configs = match (true) {
            is_numeric($identifier) => ApiConfig::where('id', $identifier)->get(),
            is_string($identifier) => ApiConfig::where('name', $identifier)->get(),
            default => ApiConfig::where('active', true)->get(),
        };

        if ($configs->isEmpty()) {
            $this->error('No API config found.');

            return self::FAILURE;
        }

        foreach ($configs as $config) {
            $this->info(sprintf('=== Sampling: %s (ID: %s) ===', $config->name, $config->id));

            try {
                $rows = match ($config->source_type) {
                    'rest' => $this->handleRest($config),
                    'database' => $this->handleDatabase($config),
                    default => throw new \Exception('Unsupported source type: '.$config->source_type),
                };

                if ($rows === []) {
                    $this->warn('No data returned.');

                    continue;
                }

                // ✅ Output modes
                if ($this->option('yaml')) {
                    $this->line(Yaml::dump($rows, 4, 2));
                } else {
                    // default = JSON
                    $this->line(json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }

            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }
        }

        $this->info('API sample complete');

        return self::SUCCESS;
    }

    protected function handleRest(ApiConfig $config): array
    {
        $cfg = $config->source_config;

        $response = Http::withHeaders($cfg['headers'] ?? [])
            ->get($cfg['url']);

        if (! $response->successful()) {
            throw new \Exception('HTTP error: '.$response->status());
        }

        $data = $response->json();

        if ($config->data_path) {
            $data = Arr::get($data, $config->data_path);
        }

        return collect($data)->take(10)->values()->toArray();
    }

    protected function handleDatabase(ApiConfig $config): array
    {
        $cfg = $config->source_config;

        $rows = DB::connection($cfg['connection'] ?? null)
            ->select($cfg['query']);

        return collect($rows)
            ->take(10)
            ->map(fn ($row): array => (array) $row)
            ->toArray();
    }
}

```

---

## File: `src\Console\Commands\GetClassMembers.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Console\Commands\GetClassMembers.php`

```php
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

```

---

## File: `src\Console\Commands\ListModelsCommand.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Console\Commands\ListModelsCommand.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Console\Commands;

use Bites\Base\Services\GetModelInfo;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('bites:list-models')]
#[Description('Command description')]
class ListModelsCommand extends Command
{
    public function handle(): int
    {
        $this->info('Discovered Models');
        $this->newLine();

        foreach (GetModelInfo::tableMap() as $table => $model) {
            $this->line(sprintf(
                '%-40s %s',
                $table,
                $model
            ));
        }

        return self::SUCCESS;
    }
}

```

---

## File: `src\Console\Commands\QuickMdPkg.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Console\Commands\QuickMdPkg.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Description('Read and display all PHP files recursively and save them to quick.md')]
#[Signature('bites:quick-md 
                            {folder : The path to the folder} 
                            {--flat : Disable deep folder scanning (check top-level only)} 
                            {--s|search= : Optional keyword filter}')]
class QuickMdPkg extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $folder = $this->argument('folder');
        $flat = $this->option('flat');
        $searchKeyword = $this->option('search');

        // Resolve path context
        $path = base_path($folder);
        if (! File::isDirectory($path)) {
            $path = $folder;
        }

        if (! File::isDirectory($path)) {
            $this->error('The directory does not exist: '.$path);

            return Command::FAILURE;
        }

        // Get matching file set
        $files = $flat ? File::files($path) : File::allFiles($path);
        $processedCount = 0;

        // Initialize Markdown content with a dynamic header block
        $markdownContent = "# PHP Files Code Dump\n";
        $markdownContent .= '*Generated on: '.now()->toDateTimeString()."*\n";
        $markdownContent .= "*Target Folder: `{$path}`*\n\n---\n\n";

        foreach ($files as $file) {
            // Skip the output file itself if it already exists to avoid infinite self-reading loops
            if ($file->getFilename() === 'quick.md') {
                continue;
            }

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $filePath = $file->getRealPath();
            $contents = File::get($filePath);

            if ($searchKeyword && ! str_contains($contents, $searchKeyword)) {
                continue;
            }

            $processedCount++;
            $relativePath = $file->getRelativePathname();

            // 1. Output content live to the command console window
            $this->info('========================================');
            $this->warn('FILE: '.$relativePath);
            $this->info('PATH: '.$filePath);
            $this->info('========================================');
            $this->line($contents);
            $this->line("\n");

            // 2. Append markdown-wrapped block segments
            $markdownContent .= "## File: `{$relativePath}`\n";
            $markdownContent .= "**Absolute Path:** `{$filePath}`\n\n";
            $markdownContent .= "```php\n";
            $markdownContent .= $contents."\n";
            $markdownContent .= "```\n\n";
            $markdownContent .= "---\n\n";
        }

        // Write or completely replace the 'quick.md' file inside the targeted folder
        $markdownFilePath = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'quick.md';
        File::put($markdownFilePath, $markdownContent);

        $this->comment('Process complete.');
        $this->comment(sprintf('Read %d file(s). Saved markdown summary to: %s', $processedCount, $markdownFilePath));

        return Command::SUCCESS;
    }
}

```

---

## File: `src\Contracts\DataFetcher.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Contracts\DataFetcher.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Contracts;

interface DataFetcher
{
    public function fetch(array $config): array;
}

```

---

## File: `src\Jobs\ProcessApiDataJob.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Jobs\ProcessApiDataJob.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Jobs;

use Bites\Base\Models\ApiData;
use Bites\Base\Services\ProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessApiDataJob implements ShouldQueue
{
    use Queueable;

    public ApiData $data;

    public function __construct(ApiData $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        app(ProcessingService::class)->process($this->data);
    }

    /**
     * ✅ Optional but recommended defaults
     */
    public $tries = 3;

    public $timeout = 120;
}

```

---

## File: `src\Models\ApiConfig.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Models\ApiConfig.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'source_type',
    'source_config',
    'data_path',
    'depends_on',
    'mapping',
    'active',
])]
class ApiConfig extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'source_config' => 'array',
            'depends_on' => 'array',
            'mapping' => 'array',
            'active' => 'boolean',
        ];
    }

    public function apiDatas(): HasMany
    {
        return $this->hasMany(ApiData::class);
    }
}

```

---

## File: `src\Models\ApiData.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Models\ApiData.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Models;

use Bites\Base\Observers\ApiDataObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(ApiDataObserver::class)]
#[Fillable([
    'api_config_id',
    'fingerprint',
    'payload',
    'status',
    'processed_at',
    'error',
])]
class ApiData extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'api_config_id' => 'integer',
            'payload' => 'array',
            'processed_at' => 'timestamp',
        ];
    }

    public function apiConfig(): BelongsTo
    {
        return $this->belongsTo(ApiConfig::class);
    }

    public function markProcessed(): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
            'error' => null,
            'payload' => 'committed',
        ]);
    }

    public function markFailed(string $message): void
    {
        $this->update([
            'status' => 'failed',
            'error' => $message,
        ]);
    }
}

```

---

## File: `src\Observers\ApiDataObserver.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Observers\ApiDataObserver.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Observers;

use Bites\Base\Jobs\ProcessApiDataJob;
use Bites\Base\Models\ApiData;
use Bites\Base\Services\ProcessingService;

class ApiDataObserver
{
    public function created(ApiData $data): void
    {
        app(ProcessingService::class)->process($data);
    }

    public function updated(ApiData $data): void
    {

        if (in_array($data->status, ['processed', 'failed'], true)) {
            return;
        }

        // ✅ Respect config: async (queue) OR sync (immediate)
        if (config('bites.sync.queue', true)) {
            ProcessApiDataJob::dispatch($data);

            return;
        }

        // ✅ Sync processing fallback
        app(ProcessingService::class)->process($data);
    }
}

```

---

## File: `src\Services\FetchService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Services\FetchService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use Bites\Base\Actions\FetchDatabaseData;
use Bites\Base\Actions\FetchRestData;
use Bites\Base\Actions\PutFingerPrint;
use Bites\Base\Models\ApiConfig;
use Bites\Base\Models\ApiData;

class FetchService
{
    public function fetch(ApiConfig $config): void
    {
        $fetcher = match ($config->source_type) {
            'rest' => new FetchRestData,
            'database' => new FetchDatabaseData,
        };

        $data = $fetcher->fetch($config->source_config);

        $items = data_get($data, $config->data_path ?? 'data', $data);

        // foreach ($items as $item) {
        ApiData::firstOrCreate(
            [
                'api_config_id' => $config->id,
                'fingerprint' => PutFingerPrint::make((array) $items),
            ],
            [
                'payload' => (array) $items,
                'status' => 'pending',
            ]
        );
        // }
    }
}

```

---

## File: `src\Services\GetModelInfo.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Services\GetModelInfo.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class GetModelInfo
{
    /**
     * Return all discovered Eloquent model classes.
     *
     * @return Collection<int, class-string<Model>>
     */
    public static function all(): Collection
    {
        return collect(self::tableMap())
            ->values()
            ->unique()
            ->values();
    }

    /**
     * Build a table-name to model-class map.
     *
     * Example:
     * [
     *     'attribute_definitions' => 'Bites\Attributing\Models\AttributeDefinition',
     *     'attribute_options' => 'Bites\Attributing\Models\AttributeOption',
     * ]
     *
     * @return array<string, class-string<Model>>
     */
    public static function tableMap(): array
    {
        $results = [];

        foreach (self::scanDirectories() as $directory) {
            self::scanDirectory($directory, $results);
        }

        ksort($results);

        return $results;
    }

    /**
     * Find an Eloquent model class by table name.
     *
     * @return class-string<Model>|null
     */
    public static function findByTable(string $tableName): ?string
    {
        return self::tableMap()[$tableName] ?? null;
    }

    /**
     * Directories to scan for models.
     *
     * @return array<int, string>
     */
    protected static function scanDirectories(): array
    {
        return array_values(array_filter([
            app_path(),

            // Local package development path.
            base_path('packages'),

            // Installed package paths that you care about.
            base_path('vendor/bit-es'),
            base_path('vendor/rimba'),
        ], static fn (string $directory): bool => is_dir($directory)));
    }

    /**
     * Scan PHP files recursively and collect table => model mappings.
     *
     * @param  array<string, class-string<Model>>  $results
     */
    protected static function scanDirectory(string $directory, array &$results): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $path = $file->getRealPath();

            if (! is_string($path)) {
                continue;
            }

            /*
             * Optional but useful optimization.
             * Only scan files inside a Models directory.
             *
             * Matching examples:
             * app/Models/User.php
             * packages/bit-es/attributing/src/Models/AttributeDefinition.php
             * vendor/bit-es/attributing/src/Models/AttributeDefinition.php
             */
            if (! str_contains(str_replace('\\', '/', $path), '/Models/')) {
                continue;
            }

            $contents = file_get_contents($path);

            if (! is_string($contents)) {
                continue;
            }

            /*
             * Fast skip.
             *
             * This works for:
             * class AttributeDefinition extends Model
             *
             * If later you use:
             * class Staff extends BaseModel
             *
             * then remove this block and let instanceof Model check below decide.
             */
            if (! str_contains($contents, 'extends Model')) {
                continue;
            }

            $fqcn = self::extractClassName($contents);

            if (! $fqcn) {
                continue;
            }

            try {
                if (! class_exists($fqcn)) {
                    continue;
                }

                $instance = new $fqcn;

                if (! $instance instanceof Model) {
                    continue;
                }

                $results[$instance->getTable()] = $fqcn;
            } catch (\Throwable) {
                continue;
            }
        }
    }

    /**
     * Extract the fully qualified class name from PHP file contents.
     */
    protected static function extractClassName(string $contents): ?string
    {
        preg_match('/namespace\s+([^;]+);/', $contents, $namespaceMatch);
        preg_match('/class\s+([A-Za-z_]\w*)/', $contents, $classMatch);

        $namespace = $namespaceMatch[1] ?? null;
        $className = $classMatch[1] ?? null;

        if (! $namespace || ! $className) {
            return null;
        }

        return trim($namespace).'\\'.trim($className);
    }
}

```

---

## File: `src\Services\MappingService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Services\MappingService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use Bites\Base\Models\ApiData;
use Illuminate\Support\Facades\DB;

class MappingService
{
    public function run(ApiData $data): void
    {
        foreach ($data->apiConfig->mapping as $entity) {
            $this->processEntity($entity, $data->payload);
        }
    }

    protected function processEntity(array $entity, array $payload, $parent = null): void
    {
        $path = $entity['path'] ?? '';
        $items = $path === '' ? $payload : data_get($payload, $path);

        if (! is_array($items)) {
            return;
        }

        if (! ($entity['many'] ?? false)) {
            $items = [$items];
        }

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [];
            $usedKeys = [];

            foreach ($entity['fields'] as $field) {
                $value = $this->resolveFieldValue($field, $item, $usedKeys);

                if (isset($field['regex']) && is_string($value)) {
                    if (str_starts_with($field['regex'], '@')) {
                        $value = $this->executePhpExpression(substr($field['regex'], 1), $value, $item, $field);
                    } else {
                        $value = preg_replace($field['regex'], '$1', $value) ?? $value;
                    }
                }

                // ✅ attribute mapping (no "to", only "into")
                if (isset($field['into']) && ! isset($field['to'])) {
                    $row['extra'][$field['into']] = $value;

                    continue;
                }

                // ✅ normal column mapping
                if (isset($field['to'])) {
                    $row[$field['to']] = $value;
                }
            }

            if (isset($entity['skip_if'])) {
                $rule = $entity['skip_if'];

                if (
                    isset($rule['field'], $rule['min_length']) &&
                    isset($row[$rule['field']]) &&
                    mb_strlen(trim((string) $row[$rule['field']])) < $rule['min_length']
                ) {
                    continue;
                }
            }

            if ($parent && isset($entity['foreign_key'])) {
                // child foreign keys link the child record back to the parent
                $row[$entity['foreign_key']] = $parent->id;
            }

            $remaining = array_diff_key(
                $item,
                array_flip($usedKeys)
            );

            $model = app(ModelSyncService::class)->sync(
                modelClass: $entity['model'],
                uniqueBy: $entity['unique_by'] ?? null,
                addAbacs: $entity['add_abac'] ?? false,
                row: $row
            );

            if ($parent && isset($entity['parent_key'])) {
                $parent->forceFill([$entity['parent_key'] => $model->id])->save();
            }

            foreach ($entity['children'] ?? [] as $child) {
                $this->processEntity($child, $item, $model);
            }
        }
    }

    protected function resolveFieldValue(array $field, array $item, array &$usedKeys)
    {
        if (array_key_exists('value', $field)) {
            return $field['value'];
        }

        if (isset($field['do'])) {
            $input = data_get($item, $field['from'] ?? null);

            if (isset($field['from']) && is_string($field['from'])) {
                $usedKeys[] = $field['from'];
            }

            return $this->executeFieldAction($field['do'], $input, $item, $field);
        }

        $value = data_get($item, $field['from'] ?? null);

        if (isset($field['from']) && is_string($field['from'])) {
            $usedKeys[] = $field['from'];
        }

        return $value;
    }

    protected function executeFieldAction(mixed $action, mixed $value, array $item, array $field)
    {
        if (is_string($action) && str_starts_with($action, '@')) {
            return $this->executePhpExpression(substr($action, 1), $value, $item, $field);
        }

        if (is_array($action)) {
            if (isset($action['artisan']) || isset($action['command'])) {
                $command = $action['artisan'] ?? $action['command'];

                if (isset($action['transform'])) {
                    $value = $this->resolveTransform($action['transform'], $value, $item, $field);
                }

                return $this->executeArtisanCommand($command, $value);
            }

            return $this->executeQueryAction($action, $value, $item, $field);
        }

        if (is_string($action) && str_starts_with($action, 'artisan:')) {
            return $this->executeArtisanCommand(substr($action, 8), $value);
        }

        throw new \InvalidArgumentException('Mapping action must be a query array, an artisan command string, or a PHP expression starting with @.');
    }

    protected function resolveTransform(mixed $transform, mixed $value, array $item, array $field)
    {
        if (is_string($transform) && str_starts_with($transform, '@')) {
            dd($this);

            return $this->executePhpExpression(substr($transform, 1), $value, $item, $field);
        }

        if (is_callable($transform)) {
            return $transform($value, $item, $field);
        }

        return $transform;
    }

    protected function executeQueryAction(array $query, mixed $value, array $item, array $field)
    {
        if (isset($query['query'])) {
            return $this->executeRawQuery($query, $value, $item, $field);
        }

        $modelClass = $query['model'] ?? null;
        if (! $modelClass || ! class_exists($modelClass)) {
            throw new \InvalidArgumentException("Query action requires a valid 'model' class.");
        }

        $queryBuilder = $modelClass::query();

        if (isset($query['where'])) {
            foreach ($query['where'] as $column => $condition) {
                if (is_string($condition) && str_contains($condition, '$value')) {
                    $condition = str_replace('$value', $value, $condition);
                }

                $queryBuilder->where($column, $condition);
            }
        }

        if (isset($query['value'])) {
            return $queryBuilder->value($query['value']);
        }

        if (isset($query['first'])) {
            $record = $queryBuilder->first();

            return $record ? $record->{$query['first']} : null;
        }

        throw new \InvalidArgumentException("Query action must specify 'value', 'first', or 'query'.");
    }

    protected function executeRawQuery(array $query, mixed $value, array $item, array $field)
    {
        $sql = $query['query'];
        $bindings = $query['bindings'] ?? [];

        // Replace $value in SQL and bindings
        $sql = str_replace('$value', '?', $sql);
        $bindings = array_map(fn ($b) => $b === '$value' ? $value : $b, $bindings);

        $result = DB::selectOne($sql, $bindings);

        if (! $result) {
            return null;
        }

        if (isset($query['column'])) {
            return $result->{$query['column']};
        }

        return $result;
    }

    protected function executeArtisanCommand(string $command, mixed $value): ?string
    {
        $command = str_replace('$value', escapeshellarg((string) $value), $command);
        $shell = PHP_BINARY.' artisan '.$command.' 2>&1';

        $output = trim(shell_exec($shell));

        return $output === '' ? null : $output;
    }

    protected function executePhpExpression(string $expression, mixed $value, array $item, array $field)
    {
        try {
            return eval('return '.$expression.';');
        } catch (\Throwable $throwable) {
            throw new \InvalidArgumentException(sprintf('PHP expression action [%s] failed: ', $expression).$throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}

```

---

## File: `src\Services\ModelSyncService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Services\ModelSyncService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use Illuminate\Database\Eloquent\Model;

class ModelSyncService
{
    public function sync(
        string $modelClass,
        ?string $uniqueBy,
        bool $addAbacs,
        array $row
    ): ?Model {
        /** @var Model $model */
        $model = new $modelClass;

        $fillable = array_flip($model->getFillable());

        // Split row
        $fillableRow = array_intersect_key($row, $fillable);
        $remaining = array_diff_key($row, $fillable);

        // ✅ Upsert / create
        if ($uniqueBy && isset($fillableRow[$uniqueBy])) {
            $model = $modelClass::query()->updateOrCreate(
                [$uniqueBy => $fillableRow[$uniqueBy]],
                $fillableRow
            );
        } else {
            $model = $modelClass::query()->create($fillableRow);
        }

        // ✅ Abacs
        if ($addAbacs && $remaining !== [] && method_exists($model, 'setAbac')) {
            foreach ($remaining as $key => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $model->setAbac($key, $value);
            }
        }

        return $model;
    }
}

```

---

## File: `src\Services\NotificationService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Services\NotificationService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use App\Models\User;
use Bites\Base\Actions\SendNotification;

class NotificationService
{
    public function send(User $user, string $message, array $context = []): void
    {
        app(SendNotification::class)
            ->execute($user, $message, $context);
    }

    public function sendToMany(iterable $users, string $message): void
    {
        foreach ($users as $user) {
            $this->send($user, $message);
        }
    }
}

```

---

## File: `src\Services\ProcessingService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\base\src\Services\ProcessingService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use Bites\Base\Models\ApiData;

class ProcessingService
{
    public function process(ApiData $data): void
    {
        dump(sprintf('Processing API data ID: %s with config: %s', $data->id, $data->apiConfig->name));
        try {
            app(MappingService::class)->run($data);
            $data->markProcessed();
        } catch (\Throwable $throwable) {
            $data->markFailed($throwable->getMessage());
            throw $throwable;
        }
    }
}

```

---

