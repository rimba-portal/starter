<?php

namespace Bites\Identity\Traits;

use Bites\Identity\Models\UserAuth;
use Filament\MultiFactorAuthentication\InteractsWithAppAuthentication;

trait HasIdentityAuth
{
    use InteractsWithAppAuthentication;

    public function auth()
    {
        return $this->hasOne(UserAuth::class);
    }

    public function getAuthAttribute()
    {
        return $this->getRelationValue('auth') ?? $this->auth()->create([]);
    }

    public function getAppAuthenticationSecret(): ?string
    {
        return $this->auth->two_factor_secret;
    }

    public function setAppAuthenticationSecret(?string $secret): void
    {
        $this->auth->update(['two_factor_secret' => $secret]);
    }

    public function getTwoFactorRecoveryCodes(): ?array
    {
        return $this->auth->two_factor_recovery_codes ? json_decode($this->auth->two_factor_recovery_codes, true) : null;
    }

    public function setTwoFactorRecoveryCodes(?array $codes): void
    {
        $this->auth->update(['two_factor_recovery_codes' => $codes ? json_encode($codes) : null]);
    }

    public function getTwoFactorConfirmedAt(): ?string
    {
        return $this->auth->two_factor_confirmed_at?->toIsoString();
    }

    public function markTwoFactorAsConfirmed(): void
    {
        $this->auth->update(['two_factor_confirmed_at' => now()]);
    }

    public function hasConfirmedTwoFactorAuthentication(): bool
    {
        return filled($this->auth->two_factor_secret) && $this->auth->two_factor_confirmed_at;
    }

    public function getFaceDescriptorAttribute()
    {
        return $this->auth->face_descriptor;
    }

    public function setFaceDescriptorAttribute($value)
    {
        $this->auth->update(['face_descriptor' => $value]);
    }

    public function isFullySetup(): bool
    {
        return $this->auth->setup_completed && filled($this->auth->face_descriptor) && $this->hasConfirmedTwoFactorAuthentication();
    }
}