<?php

namespace Bites\Identity\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\PasswordInput;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Schema;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use App\Models\User;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    // protected string $view = 'bites-identity::auth.login';
    protected ?string $storedFace = null;
    protected ?User $resolvedUser = null;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                
                Step::make('Username')
                    ->key('step-username')
                    ->schema([
                        TextInput::make('username')->label('Username/Email')->required()->autofocus(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->beforeValidation(function (array $state) {
                        // FIX: Use $this-> to assign the property to the class instance
                        $this->resolvedUser = User::where('username', $state['username'])
                            ->orWhere('email', $state['username'])
                            ->first();

                        if (! $this->resolvedUser) {
                            throw ValidationException::withMessages([
                                'username' => 'Account not found.',
                            ]);
                        }
                    })
                    ->afterValidation(function (array $state, $component) {
                        // FIX: Use $this-> to access the model resolved in the previous hook
                        $authenticated = Auth::validate([
                            'email' => $this->resolvedUser->email, 
                            'password' => $state['password']
                        ]);

                        if (! $authenticated) {
                            throw ValidationException::withMessages([
                                'password' => 'Invalid password.',
                            ]);
                        }

                        // FIX: Store the descriptor using $this->
                        $this->storedFace = $this->resolvedUser->face_descriptor;
// dd($this->storedFace);
                        // if (! $this->storedFace) {
                        //     throw ValidationException::withMessages([
                        //         'username' => 'Biometric login profile not set up.',
                        //     ]);
                        // }

                        // Synchronously move the wizard forward on the backend layout state tree
                        $wizard = $component->getContainer()->getParentComponent();
                        // $wizard->nextStep(currentStepIndex: 0);
                        
                    }),

                Step::make('Face Verification')
                    ->key('step-face')
                    ->schema([
                        TextInput::make('face_ok')
                            ->hidden()
                            ->required()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, $livewire) {
                                if ((int) $state === 1) {
                                    $livewire->call('authenticate');
                                }
                            }),

                        TextInput::make('stored_face')
                            ->hidden()
                            // FIX: Pull directly from the class state container
                            ->default(fn () => $this->storedFace),
                    ]),

            ])->persistStepInQueryString()->submitAction(new HtmlString('<button type="submit">Submit</button>'))
        ]);
    }
   protected function getFormActions(): array
    {
        return [];
    }
    public function authenticate(): ?LoginResponse
    {
        if (($this->data['face_ok'] ?? 0) != 1) {
            throw ValidationException::withMessages([
                'face_ok' => 'Face confirmation failed.',
            ]);
        }

        // FIX: Use the class model snapshot to finalize session authorizations
        Auth::login($this->resolvedUser, $this->data['remember'] ?? false);
        session()->regenerate();

        $targetUrl = $this->resolvedUser->isFullySetup() 
            ? route('filament.admin.pages.dashboard') 
            : route('filament.admin.pages.profile');

        session()->put('url.intended', $targetUrl);

        return app(LoginResponse::class);
    }
}
