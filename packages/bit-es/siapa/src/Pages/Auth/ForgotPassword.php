<?php

namespace Bites\Identity\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class ForgotPassword extends RequestPasswordReset
{
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('email')->email()->required()->exists('users')->live(onBlur: true),
            TextInput::make('totp_code')->numeric()->length(6)->required()->visible(fn ($get) => filled($get('email')))
                ->rule(function ($attr, $val, $fail) {
                    $user = \App\Models\User::where('email', $this->data['email'])->firstOrFail();
                    if (!$user->verifyTwoFactorCode($val)) $fail('Invalid TOTP code');
                })
        ]);
    }
}