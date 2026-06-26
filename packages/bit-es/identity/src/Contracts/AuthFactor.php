<?php

declare(strict_types=1);

namespace Rimba\Identity\Contracts;

use Rimba\Identity\Models\IdentityProfile;

interface AuthFactor
{
    public function name(): string;

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool;
}
