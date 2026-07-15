# PHP Files Code Dump
*Generated on: 2026-07-15 16:27:06*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\siapa`*

---

## File: `config\identity.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\config\identity.php`

```php
<?php

declare(strict_types=1);

return [
    'auto_register' => true,
    'face_match_threshold' => 0.6, // Lower = stricter match
];

```

---

## File: `database\migrations\0002_01_01_000201_create_siapa_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\database\migrations\0002_01_01_000201_create_siapa_tables.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_auth', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->text('face_descriptor')->nullable();
            $table->boolean('setup_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_auth');
    }
};

```

---

## File: `resources\views\auth\login.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\resources\views\auth\login.blade.php`

```php
<x-filament-panels::page.simple>
    <h2 class="text-center text-2xl font-bold mb-6">Secure Login</h2>
    {{ $this->form }}

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        let modelsLoaded = false;
        const THRESHOLD = {{ config('identity.face_match_threshold', 0.6) }};

        async function loadModels() {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            modelsLoaded = true;
        }

        window.addEventListener('start-face-scan', async () => {
            if (!modelsLoaded) await loadModels();
            const stored = JSON.parse(document.querySelector('input[name="stored_face"]').value);
            const matcher = new faceapi.FaceMatcher(new faceapi.LabeledFaceDescriptors('user', [new Float32Array(stored)]), THRESHOLD);

            const step = document.querySelector('[data-step-id="face-verification"]');
            step.innerHTML += `<div class="mt-6 text-center"><video id="cam" autoplay muted playsinline width="320" height="240" class="mx-auto border rounded"></video><p id="status" class="mt-4"></p></div>`;

            const cam = document.getElementById('cam');
            const status = document.getElementById('status');
            const faceOk = document.querySelector('input[name="face_ok"]');
            const form = document.querySelector('form');
            cam.srcObject = await navigator.mediaDevices.getUserMedia({video: true});

            const check = setInterval(async () => {
                const d = await faceapi.detectSingleFace(cam).withFaceLandmarks().withFaceDescriptor();
                if (!d) return status.textContent = '❌ No face detected';
                const match = matcher.findBestMatch(d.descriptor);
                status.textContent = `🔍 Match: ${(1 - match.distance).toFixed(2)}`;
                if (match.distance <= THRESHOLD) {
                    status.textContent = '✅ Matched! Logging in...';
                    faceOk.value = '1';
                    clearInterval(check);
                    setTimeout(() => form.submit(), 800);
                }
            }, 1200);
        });
    </script>
</x-filament-panels::page.simple>
```

---

## File: `resources\views\profile.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\resources\views\profile.blade.php`

```php
<x-filament-panels::page>
    @if (!auth()->user()->isFullySetup())
        <div class="p-4 mb-6 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h3 class="font-semibold text-yellow-800">Required Setup</h3>
            <p class="text-sm text-yellow-700">Complete both steps to access all features.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($this->getActions() as $action) {{ $action }} @endforeach
    </div>

    <x-filament::modal id="face-setup-modal" width="md">
        <x-slot name="heading">Set Up Face Verification</x-slot>
        <div class="text-center">
            <video id="cam" autoplay muted playsinline width="300" height="220" class="mx-auto border rounded"></video>
            <p id="status" class="mt-3 text-gray-600">Position your face clearly</p>
        </div>
        <x-slot name="footerActions">
            <x-filament::button id="save-face" color="primary">Save Face</x-filament::button>
        </x-slot>
    </x-filament::modal>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        let modelsLoaded = false, descriptor = null;
        async function loadModels() {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            modelsLoaded = true;
        }

        document.addEventListener('open-face-setup-modal', async () => {
            if (!modelsLoaded) await loadModels();
            const video = document.getElementById('cam');
            const status = document.getElementById('status');
            video.srcObject = await navigator.mediaDevices.getUserMedia({video: true});

            const check = setInterval(async () => {
                const d = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
                if (d) { descriptor = d.descriptor; status.textContent = '✅ Ready to save'; status.className = 'text-green-600'; }
                else { descriptor = null; status.textContent = '❌ No clear face'; status.className = 'text-red-500'; }
            }, 1000);

            document.getElementById('save-face').onclick = async () => {
                if (!descriptor) return;
                await fetch('{{ route("profile.save-face") }}', {
                    method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                    body: JSON.stringify({descriptor: Array.from(descriptor)})
                });
                window.location.reload();
            };

            document.getElementById('face-setup-modal').addEventListener('close', () => {
                clearInterval(check);
                video.srcObject?.getTracks().forEach(t => t.stop());
            });
        });
    </script>
</x-filament-panels::page>
```

---

## File: `routes\web.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\routes\web.php`

```php
<?php

declare(strict_types=1);

use Bites\Identity\Http\Controllers\IdentityController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->post('/profile/save-face', [IdentityController::class, 'saveFace'])->name('profile.save-face');

```

---

## File: `src\Http\Controllers\IdentityController.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Http\Controllers\IdentityController.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IdentityController extends Controller
{
    public function saveFace(Request $request)
    {
        $request->validate(['descriptor' => 'required|array']);
        auth()->user()->auth->update(['face_descriptor' => json_encode($request->descriptor)]);

        return response()->json(['success' => true]);
    }
}

```

---

## File: `src\Http\Middleware\EnsureSetupIsComplete.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Http\Middleware\EnsureSetupIsComplete.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        if (! $user->isFullySetup()) {
            $allowed = ['admin/login', 'admin/logout', 'admin/profile', 'admin/profile/mfa'];
            foreach ($allowed as $path) {
                if ($request->is($path) || $request->is($path.'/*')) {
                    return $next($request);
                }
            }

            return redirect()->route('filament.admin.pages.profile')
                ->with('warning', 'Complete Face and TOTP setup first.');
        }

        return $next($request);
    }
}

```

---

## File: `src\IdentityServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\IdentityServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity;

use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function registerPackage(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/identity.php', 'identity');
    }

    public function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bites-identity');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/identity.php' => config_path('identity.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/assets/models' => public_path('models'),
        ], 'assets');

    }
}

```

---

## File: `src\Models\UserAuth.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Models\UserAuth.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'two_factor_secret', 'two_factor_recovery_codes',
    'two_factor_confirmed_at', 'face_descriptor', 'setup_completed',
])]
#[Table(name: 'user_auth')]
class UserAuth extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    protected function casts(): array
    {
        return [
            'two_factor_confirmed_at' => 'datetime',
            'setup_completed' => 'boolean',
        ];
    }
}

```

---

## File: `src\Pages\Auth\ForgotPassword.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Pages\Auth\ForgotPassword.php`

```php
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

```

---

## File: `src\Pages\Auth\Login.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Pages\Auth\Login.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Pages\Auth;

use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

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
                    ->beforeValidation(function (array $state): void {
                        // FIX: Use $this-> to assign the property to the class instance
                        $this->resolvedUser = User::where('email', '=', $this->data['email'], 'and')
                            ->orWhere('email', $state['username'])
                            ->first();

                        if (! $this->resolvedUser instanceof User) {
                            throw ValidationException::withMessages([
                                'username' => 'Account not found.',
                            ]);
                        }
                    })
                    ->afterValidation(function (array $state, $component): void {
                        // FIX: Use $this-> to access the model resolved in the previous hook
                        $authenticated = Auth::validate([
                            'email' => $this->resolvedUser->email,
                            'password' => $state['password'],
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
                            ->afterStateUpdated(function ($state, $livewire): void {
                                if ((int) $state === 1) {
                                    $livewire->call('authenticate');
                                }
                            }),

                        TextInput::make('stored_face')
                            ->hidden()
                            // FIX: Pull directly from the class state container
                            ->default(fn (): ?string => $this->storedFace),
                    ]),

            ])->persistStepInQueryString()->submitAction(new HtmlString('<button type="submit">Submit</button>')),
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

```

---

## File: `src\Pages\Auth\Register.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Pages\Auth\Register.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function redirectTo(): string
    {
        return route('filament.admin.pages.profile');
    }
}

```

---

## File: `src\Pages\Profile.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Pages\Profile.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    // protected static string $view = 'bites-identity::profile';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $title = 'Account Setup';

    public function mount(): void
    {
        abort_unless(Auth::check(), 403);
    }

    protected function getActions(): array
    {
        $user = Auth::user();

        return [
            Action::make('setup_face')
                ->label($user->face_descriptor ? '✅ Face Verified' : '📸 Set Up Face')
                ->color($user->face_descriptor ? 'success' : 'primary')
                ->disabled(filled($user->face_descriptor))
                ->action(fn () => $this->dispatch('open-face-setup-modal')),
            Action::make('setup_totp')
                ->label($user->hasConfirmedTwoFactorAuthentication() ? '✅ TOTP Active' : '🔑 Set Up TOTP')
                ->color($user->hasConfirmedTwoFactorAuthentication() ? 'success' : 'primary')
                ->url(fn (): string => route('filament.admin.pages.profile.mfa')),
            Action::make('mark_complete')
                ->label('✅ Complete Setup')
                ->color('success')
                ->visible(fn (): bool => ! $user->auth->setup_completed)
                ->disabled(fn (): bool => ! filled($user->face_descriptor) || ! $user->hasConfirmedTwoFactorAuthentication())
                ->action(function () use ($user): void {
                    $user->auth->update(['setup_completed' => true]);
                    Notification::make()->success()->title('Setup complete!')->send();
                }),
        ];
    }
}

```

---

## File: `src\Traits\HasIdentityAuth.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\siapa\src\Traits\HasIdentityAuth.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Identity\Traits;

use Bites\Identity\Models\UserAuth;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasIdentityAuth
{
    use InteractsWithAppAuthentication;

    public function auth(): HasOne
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

    public function setFaceDescriptorAttribute($value): void
    {
        $this->auth->update(['face_descriptor' => $value]);
    }

    public function isFullySetup(): bool
    {
        return $this->auth->setup_completed && filled($this->auth->face_descriptor) && $this->hasConfirmedTwoFactorAuthentication();
    }
}

```

---

