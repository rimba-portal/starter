<?php

namespace Bites\Identity\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\PasswordInput;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class Login extends BaseLogin
{
    protected static string $view = 'bites-identity::auth.login';
    protected ?string $storedFace = null;

    public function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Step::make('Username')
                    ->schema([TextInput::make('username')->label('Username/Email')->required()->autofocus()]),
                Step::make('Password')
                    ->schema([PasswordInput::make('password')->label('Password')->required()])
                    ->validateUsing(function ($data) {
                        if (!Auth::attempt(['username' => $data['username'], 'password' => $data['password']])
                            && !Auth::attempt(['email' => $data['username'], 'password' => $data['password']])) {
                            throw ValidationException::withMessages(['password' => 'Invalid credentials']);
                        }
                        $this->storedFace = Auth::user()->face_descriptor;
                        if (!$this->storedFace) throw ValidationException::withMessages(['password' => 'Face not set up']);
                    }),
                Step::make('Face Verification')
                    ->schema([
                        TextInput::make('face_ok')->hidden()->required()->default(0),
                        TextInput::make('stored_face')->hidden()->default(fn () => $this->storedFace)
                    ])
                    ->afterStateUpdated(fn () => $this->dispatch('start-face-scan'))
            ])->persistStepInQueryString()
        ]);
    }

    protected function authenticate(): void
    {
        if ($this->data['face_ok'] != 1) throw ValidationException::withMessages(['face_ok' => 'Face match failed']);
        redirect()->route(auth()->user()->isFullySetup() ? 'filament.admin.pages.dashboard' : 'filament.admin.pages.profile');
    }
}