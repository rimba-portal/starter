<?php

declare(strict_types=1);

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
                sprintf('Driver [%s] not registered.', $name)
            );
        }

        return app(
            $this->drivers[$name]
        );
    }
}
