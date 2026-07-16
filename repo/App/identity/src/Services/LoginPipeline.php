<?php

declare(strict_types=1);

namespace Rimba\Identity\Services;

use Illuminate\Support\Facades\Auth;
use Rimba\Identity\Managers\PipelineManager;
use Rimba\Identity\Models\IdentityProfile;

class LoginPipeline
{
    public function __construct(
        protected PipelineManager $pipeline
    ) {}

    public function login(
        IdentityProfile $profile,
        array $payload
    ): bool {

        if (! $profile->is_enabled) {
            return false;
        }

        if (
            ! $this->pipeline->verify(
                $profile,
                $payload
            )
        ) {
            return false;
        }

        Auth::guard(
            config('identity.guard')
        )->login(
            $profile->personable
        );

        return true;
    }
}
