<?php

namespace Rimba\Identity\Managers;

use InvalidArgumentException;

class IdentityManager
{
    protected array $drivers = [];

    public function extend(
        string $name,
        string $driver
    ): void {

        $this->drivers[$name] = $driver;
    }

    public function driver(
        string $name
    ) {

        if (! isset($this->drivers[$name])) {
            throw new InvalidArgumentException(
                "Driver [$name] not registered."
            );
        }

        return app(
            $this->drivers[$name]
        );
    }
}