<?php

declare(strict_types=1);

namespace Bites\Identity\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ForgotPassword extends RequestPasswordReset
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('email')->email()->required()->exists('users')->live(onBlur: true),
            TextInput::make('totp_code')->numeric()->length(6)->required()->visible(fn ($get): bool => filled($get('email')))
                ->rule(function ($attr, $val, $fail): void {
                    $user = User::where('email', '=', $this->data['email'], 'and')->firstOrFail();
                    if (! $user->verifyTwoFactorCode($val)) {
                        $fail('Invalid TOTP code');
                    }
                }),
        ]);
    }
}
