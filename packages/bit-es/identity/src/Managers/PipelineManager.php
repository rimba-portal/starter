<?php

namespace Rimba\Identity\Managers;

use Rimba\Identity\Models\IdentityProfile;

class PipelineManager
{
    public function __construct(
        protected IdentityManager $identity
    ) {
    }

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool {

        foreach (
            config('identity.pipeline', [])
            as $factor
        ) {

            $driver = $this->identity->driver(
                $factor
            );

            if (! $driver->verify(
                $profile,
                $payload[$factor] ?? []
            )) {

                return false;
            }
        }

        return true;
    }
}