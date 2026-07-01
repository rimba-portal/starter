<?php

namespace Bites\Identity\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function redirectTo(): string
    {
        return route('filament.admin.pages.profile');
    }
}