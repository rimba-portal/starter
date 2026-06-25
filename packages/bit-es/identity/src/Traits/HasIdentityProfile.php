<?php

namespace Rimba\Identity\Traits;

use Rimba\Identity\Models\IdentityProfile;

trait HasIdentityProfile
{
    public function identityProfile()
    {
        return $this->morphOne(
            IdentityProfile::class,
            'personable'
        );
    }
}