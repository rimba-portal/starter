<?php

declare(strict_types=1);

namespace Rimba\Identity\Factors;

use Illuminate\Support\Facades\Hash;
use Rimba\Identity\Contracts\AuthFactor;
use Rimba\Identity\Models\IdentityProfile;

class PinFactor implements AuthFactor
{
    public function name(): string
    {
        return 'pin';
    }

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool {

        $credential = $profile
            ->credentials()
            ->where('factor_type', 'pin')
            ->first();

        if (! $credential) {
            return false;
        }

        return Hash::check(
            $payload['pin'],
            $credential->value
        );
    }
}
