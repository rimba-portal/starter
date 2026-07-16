<?php

declare(strict_types=1);

namespace Bites\Base;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class BaseServiceProvider extends ServiceProvider
{
    protected string $configFile = __DIR__ . '/../config/bites.php';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->registerCommandsFromDirectory();
        }
    }

    /**
     * Dynamically discover and boot all commands inside the package directory.
     */
    protected function registerCommandsFromDirectory()
    {
        $commandDir = __DIR__ . '/Console/Commands';
        // Ensure the folder exists before scanning
        if (! is_dir($commandDir)) {
            return;
        }

        $commands = [];

        // Loop through all PHP files in your package's command directory
        foreach (glob($commandDir . '/*.php') as $file) {
            $className = basename($file, '.php');

            // Reconstruct the exact fully qualified namespace
            $class = 'Bites\\Base\\Console\\Commands\\' . $className;

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
