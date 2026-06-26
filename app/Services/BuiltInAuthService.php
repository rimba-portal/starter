<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AuthContract;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;

class BuiltInAuthService implements AuthContract
{
    public function authenticate(
        string $login,
        string $password,
        bool $remember
    ): string {

        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        $user = $isEmail
            ? User::where('email', $login)->first()
            : User::where('name', $login)->first();

        if (! $user) {
            return 'not_found';
        }

        if (! Hash::check($password, $user->password)) {
            return 'local_failed';
        }

        Filament::auth()->login($user, $remember);

        session()->regenerate();

        return 'local_success';
    }
}
