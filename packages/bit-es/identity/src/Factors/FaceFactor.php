<?php

declare(strict_types=1);

namespace Rimba\Identity\Factors;

use Rimba\Identity\Contracts\AuthFactor;
use Rimba\Identity\Models\IdentityProfile;

class FaceFactor implements AuthFactor
{
    public function name(): string
    {
        return 'face';
    }

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool {

        $distance = $payload['distance'];

        return $distance <= config(
            'identity.face_threshold',
            0.50
        );
    }
}
