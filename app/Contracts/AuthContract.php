<?php

declare(strict_types=1);

namespace App\Contracts;

interface AuthContract
{
    public function authenticate(
        string $login,
        string $password,
        bool $remember
    ): string;
}
