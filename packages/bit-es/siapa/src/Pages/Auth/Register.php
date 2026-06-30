<?php

namespace Bites\Identity\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;

class CustomRegister extends BaseRegister
{
    protected function redirectTo(): string
    {
        return route('filament.admin.pages.profile');
    }
}