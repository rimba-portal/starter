# PHP Files Code Dump
*Generated on: 2026-07-15 16:27:17*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who`*

---

## File: `resources\views\face-auth.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\resources\views\face-auth.blade.php`

```php
@php
    $staffNo = $getStaffNo();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="faceVerificationComponent()"
        class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-900/40">
        {{-- Status --}}
        <div class="flex flex-col">
            <span class="text-[10px] font-medium text-gray-500" x-text="referenceStatus"></span>

            <span class="text-[10px] font-semibold text-primary-600 dark:text-primary-400" x-text="cameraStatus"></span>
        </div>
        <div class="text-danger">
            STAFF: {{ $getStaffNo() }}
        </div>
        {{-- Camera --}}
        <div class="relative">
            <div
                class="h-24 w-24 overflow-hidden rounded-lg border border-gray-200 bg-black shadow-inner dark:border-gray-700">
                <video x-ref="video" autoplay muted playsinline
                    class="h-full w-full scale-x-[-1] object-cover"></video>
            </div>

            {{-- Reference Image --}}
            <div
                class="absolute -bottom-1 -right-1 h-10 w-10 overflow-hidden rounded border-2 border-white bg-gray-100 shadow dark:bg-gray-800">
                <template x-if="photoSrc">
                    <img id="referenceImage">

                <template x-if="! photoSrc">
                    <div class="flex h-full items-center justify-center text-[8px] text-gray-400">
                        No ID
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-dynamic-component>

@once
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        window.faceModelsReady ??= (async () => {
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.data('faceVerificationComponent', () => ({
                photoSrc: null,
                targetDescriptor: null,
                referenceStatus: 'Awaiting ID',
                cameraStatus: 'Offline',
                stream: null,

                async init() {
                    await this.startCamera();
                    await window.faceModelsReady;

                    this.referenceStatus = 'Ready';

                    this.$watch(
                        () => this.$wire.data.staff_no,
                        async (staffNo) => {
                            if (!staffNo) {
                                return;
                            }

                            this.photoSrc = `/pic/${staffNo}`;
                            this.referenceStatus = 'Loading ID...';

                            await this.$nextTick();
                            await this.loadReferenceDescriptor();
                        }
                    );
                },

                async startCamera() {
                    this.cameraStatus = 'Starting...';

                    await this.$nextTick();

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                width: 160,
                                height: 160,
                                facingMode: 'user',
                            },
                        });

                        this.$refs.video.srcObject = this.stream;
                        await this.$refs.video.play();

                        this.cameraStatus = 'Live';
                    } catch (error) {
                        console.error(error);
                        this.cameraStatus = 'Blocked ❌';
                    }
                },

                async loadReferenceDescriptor() {
                    const img = this.$refs.refImage;

                    if (!img) {
                        return;
                    }

                    if (!img.complete) {
                        await new Promise(resolve => {
                            img.onload = resolve;
                        });
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            img,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.referenceStatus = 'No Face ❌';
                        return;
                    }

                    this.targetDescriptor = detection.descriptor;
                    this.referenceStatus = 'Reference Ready ✅';

                    this.detectLoop();
                },

                async detectLoop() {
                    if (!this.targetDescriptor || !this.$refs.video) {
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            this.$refs.video,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (detection) {
                        const distance = faceapi.euclideanDistance(
                            this.targetDescriptor,
                            detection.descriptor
                        );
                        console.log('img src', img.src);

                        console.log('img size', img.naturalWidth, img.naturalHeight);
                        if (distance <= 0.55) {
                            this.cameraStatus = 'Match ✅';

                            this.stream?.getTracks().forEach(track => track.stop());

                            this.$wire.faceMatched();

                            return;
                        }

                        this.cameraStatus = `Dist: ${distance.toFixed(2)}`;
                    } else {
                        this.cameraStatus = 'Scanning...';
                    }

                    requestAnimationFrame(() => this.detectLoop());
                },
            }));
        });
    </script>
@endonce

```

---

## File: `resources\views\webcam.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\resources\views\webcam.blade.php`

```php
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div 
        x-data="{
            stream: null,
            photoUrl: null,
            
            init() {
                // Initialize state from existing value if any
                this.photoUrl = @js($getState());
            },

            startCamera() {
                navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                    .then(stream => {
                        this.stream = stream;
                        this.$refs.video.srcObject = stream;
                    })
                    .catch(err => {
                        console.error('Webcam not found or permission denied:', err);
                        alert('Unable to access webcam.');
                    });
            },

            takePhoto() {
                const video = this.$refs.video;
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Convert frame to base64
                this.photoUrl = canvas.toDataURL('image/jpeg');
                
                // Update Filament's wire:model / state
                $wire.set('{{ $getStatePath() }}', this.photoUrl);
                
                this.stopCamera();
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
            }
        }"
        class="space-y-4"
    >
        <!-- Camera Live View -->
        <div class="relative rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 p-2 flex justify-center">
            <video x-ref="video" autoplay playsinline class="w-full max-w-md rounded-lg" x-show="stream"></video>
            
            <!-- Snapshot Preview -->
            <img :src="photoUrl" x-show="photoUrl && !stream" class="w-full max-w-md rounded-lg" alt="Captured Photo">
            
            <div x-show="!stream && !photoUrl" class="py-16 text-center text-gray-400 dark:text-gray-500">
                Webcam is turned off
            </div>
        </div>

        <!-- Controls -->
        <div class="flex gap-2">
            <button 
                type="button" 
                @click="startCamera()" 
                x-show="!stream"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-500 rounded-lg shadow-sm"
            >
                Start Camera
            </button>

            <button 
                type="button" 
                @click="takePhoto()" 
                x-show="stream"
                class="px-4 py-2 text-sm font-medium text-white bg-success-600 hover:bg-success-500 rounded-lg shadow-sm"
            >
                Capture Photo
            </button>

            <button 
                type="button" 
                @click="stopCamera(); photoUrl = null; $wire.set('{{ $getStatePath() }}', null)" 
                x-show="photoUrl || stream"
                class="px-4 py-2 text-sm font-medium text-white bg-danger-600 hover:bg-danger-500 rounded-lg shadow-sm"
            >
                Clear
            </button>
        </div>
    </div>
</x-dynamic-component>

```

---

## File: `src\Components\FaceAuth.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\src\Components\FaceAuth.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Who\Components;

use Closure;
use Filament\Forms\Components\Field;

class FaceAuth extends Field
{
    protected string $view = 'bites::face-auth';

    protected string|Closure|null $staffNo = null;

    public function staffNo(string|Closure|null $staffNo): static
    {
        $this->staffNo = $staffNo;

        return $this;
    }

    public function getStaffNo(): ?string
    {
        return $this->evaluate($this->staffNo);
    }
}

```

---

## File: `src\Components\WebCam.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\src\Components\WebCam.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Who\Components;

use Filament\Forms\Components\Field;

class Webcam extends Field
{
    // Point to your custom blade view
    protected string $view = 'bites::webcam';
}

```

---

## File: `src\Http\UI\Auth\LoginWizard.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\src\Http\UI\Auth\LoginWizard.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Who\Http\UI\Auth;

use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Rimba\Bark\Who\Components\FaceAuth;
use Rimba\Bark\Who\Components\Webcam;

class LoginWizard extends BaseLogin
{
    public ?array $data = [];

    protected function getFormActions(): array
    {
        return [];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('ID & Pin')
                        ->afterValidation(function (): void {
                            $this->validatePasswordFactor();
                        })
                        ->schema([
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getRememberFormComponent(),
                            Webcam::make('captured_image'),
                        ]),

                    Wizard\Step::make('Face ID')
                        ->schema([
                            TextInput::make('staff_no')
                                ->readOnly(),
                            FaceAuth::make('face')
                                ->staffNo(fn ($livewire) => $livewire->data['staff_no'] ?? null)
                                ->live(),

                        ]),
                ])
                    ->skippable(false)
                    ->submitAction(new HtmlString('
                        <button type="submit" class="fi-btn fi-btn-size-md fi-color-primary">
                            Sign in
                        </button>
                    ')),
            ])
            ->statePath('data');
    }

    protected function validatePasswordFactor(): void
    {
        $data = $this->data;

        $user = User::query()
            ->where('email', $data['email'] ?? null)
            ->first();

        if (! $user || ! Hash::check($data['password'] ?? '', $user->password)) {
            throw ValidationException::withMessages([
                'data.email' => 'Invalid credentials.',
            ]);
        }

        /*
         * If user has no staff record or no staff number:
         * - login immediately
         * - redirect to user panel
         * - stop wizard from proceeding to Step 2
         */
        if (! $user->staff || blank($user->staff->staff_no)) {
            Auth::login(
                $user,
                (bool) ($data['remember'] ?? false),
            );

            session()->regenerate();

            $this->redirectToUserPanel();

            throw new Halt;
        }

        /*
         * If user has staff + staff_no:
         * - do not login yet
         * - store pending auth session
         * - populate staff_no into Step 2
         * - wizard proceeds to Face ID step
         */
        session([
            'auth.pending_user_id' => $user->id,
            'auth.pending_remember' => (bool) ($data['remember'] ?? false),
        ]);

        $this->data['staff_no'] = $user->staff->staff_no;

        $this->sendOtpToUser($user);
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->data;

        $pendingUserId = session('auth.pending_user_id');

        if (! $pendingUserId) {
            throw ValidationException::withMessages([
                'data.otp' => 'Your login session has expired. Please start again.',
            ]);
        }

        $user = User::query()->find($pendingUserId);

        if (! $user) {
            throw ValidationException::withMessages([
                'data.otp' => 'Invalid login session.',
            ]);
        }

        if (! $this->validateOtpForUser($user, $data['otp'] ?? null)) {
            throw ValidationException::withMessages([
                'data.otp' => 'Invalid authentication code.',
            ]);
        }

        Auth::login(
            $user,
            (bool) session('auth.pending_remember', false),
        );

        session()->forget([
            'auth.pending_user_id',
            'auth.pending_remember',
        ]);

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function redirectToUserPanel(): void
    {
        $this->redirect(
            filament()->getPanel('user')->getUrl()
        );
    }

    protected function sendOtpToUser(User $user): void
    {
        /*
         * Replace this later with your real OTP / Face ID preparation.
         *
         * Example:
         *
         * $code = random_int(100000, 999999);
         *
         * cache()->put(
         *     "login_otp:{$user->id}",
         *     Hash::make((string) $code),
         *     now()->addMinutes(5),
         * );
         *
         * Mail::to($user)->send(new LoginOtpMail($code));
         */
    }

    protected function validateOtpForUser(User $user, ?string $otp): bool
    {
        /*
         * Demo only.
         * Replace this with your real OTP / Face ID validation.
         */

        return $otp === '123456';
    }
}

```

---

## File: `src\WhoServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\src\WhoServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Who;

use Bites\Base\Services\BitesServiceProvider;

class WhoServiceProvider extends BitesServiceProvider
{
    protected string $viewsPath = __DIR__.'/../resources/views';

    protected function bootPackage(): void
    {
        // dd(app('view')->getFinder()->getHints());
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

