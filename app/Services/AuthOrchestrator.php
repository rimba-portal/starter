<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AuthContract;

class AuthOrchestrator
{
    /**
     * @param  array<AuthContract>  $services
     */
    public function __construct(
        protected array $services = [],
    ) {}

    public function authenticate(
        string $login,
        string $password,
        bool $remember = false
    ): string {

        foreach ($this->services as $service) {

            $result = $service->authenticate(
                $login,
                $password,
                $remember
            );

            if ($result !== 'not_found') {
                return $result;
            }
        }

        return 'not_found';
    }
}
