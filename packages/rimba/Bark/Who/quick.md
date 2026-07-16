# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:04*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Bark/Who/src' => 'Rimba\Bark\Who',
        ],
    ],
];

```

---

## File: `database\migrations\0002_01_01_000201_create_siapa_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\database\migrations\0002_01_01_000201_create_siapa_tables.php`

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
            $table->boolean('is_staff')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_face_auth')->nullable();
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

## File: `resources\views\face-auth.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\resources\views\face-auth.blade.php`

```php
@php
    $staffNo = $getStaffNo();
    $matchThreshold= 0.5
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="faceVerificationComponent({
        staffNo: @js($staffNo),
        matchThreshold: @js($matchThreshold),
    })"
        class="rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-900/40">
        {{-- Reference Avatar --}}
        <img id="referenceImage" x-ref="referenceImage" hidden alt="Reference Image">
        <div class="flex items-center gap-4">


            {{-- Camera --}}
            <div
                class="h-24 w-24 overflow-hidden rounded-lg border border-gray-200 bg-black shadow-inner dark:border-gray-700">
                <video x-ref="video" autoplay muted playsinline class="h-full w-full scale-x-[-1] object-cover"></video>
            </div>

            {{-- Information --}}
            <div class="flex-1 text-sm">
                <div class="font-medium text-gray-500 dark:text-gray-400">
                    Staff:
                    <span class="font-semibold text-gray-900 dark:text-white" x-text="staffNo || 'N/A'"></span>
                </div>

                <div class="mt-1 font-semibold"
                    :class="isMatched
                        ?
                        'text-success-600' :
                        'text-gray-900 dark:text-white'"
                    x-text="resultText"></div>
            </div>

        </div>
    </div>
</x-dynamic-component>

@once
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        window.faceModelsReady ??= (async () => {
            const MODEL_URL = '/models';
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.data('faceVerificationComponent', (config = {}) => ({
                staffNo: config.staffNo ?? null,
                matchThreshold: config.matchThreshold ?? 0.5,

                referenceDescriptor: null,
                videoStream: null,
                isMatched: false,
                isDetecting: false,

                referenceStatus: 'Awaiting ID',
                cameraStatus: 'Offline',
                resultText: 'Waiting',
                distanceText: null,

                async init() {
                    await this.$nextTick();

                    await this.startCamera();
                    await window.faceModelsReady;

                    this.referenceStatus = 'Models Ready';

                    if (this.staffNo) {
                        await this.loadReferenceImage(this.staffNo);
                    }

                    this.watchStaffNo();
                },

                watchStaffNo() {
                    this.$watch(
                        () => this.$wire?.data?.staff_no,
                        async (staffNo) => {
                            if (!staffNo) {
                                return;
                            }

                            if (staffNo === this.staffNo && this.referenceDescriptor) {
                                return;
                            }

                            this.staffNo = staffNo;

                            await this.loadReferenceImage(staffNo);
                        }
                    );
                },

                async loadReferenceImage(staffNo) {
                    const image = this.$refs.referenceImage;

                    if (!image) {
                        this.referenceStatus = 'Image Element Missing ❌';
                        return;
                    }

                    this.referenceDescriptor = null;
                    this.isMatched = false;
                    this.distanceText = null;
                    this.resultText = 'Loading ID';
                    this.referenceStatus = 'Loading ID...';

                    image.onload = async () => {
                        await this.loadReferenceDescriptor();
                    };

                    image.onerror = () => {
                        this.referenceStatus = 'Image Load Failed ❌';
                        this.resultText = 'No ID Image';
                    };

                    image.src = `/pic/${staffNo}`;
                },

                async loadReferenceDescriptor() {
                    const image = this.$refs.referenceImage;

                    if (!image) {
                        this.referenceStatus = 'Image Element Missing ❌';
                        return;
                    }

                    if (!image.complete || image.naturalWidth === 0) {
                        this.referenceStatus = 'Image Not Ready ❌';
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            image,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.referenceStatus = 'No Face In ID ❌';
                        this.resultText = 'No Reference Face';
                        return;
                    }

                    this.referenceDescriptor = detection.descriptor;
                    this.referenceStatus = 'Reference Ready ✅';
                    this.resultText = 'Scanning';

                    this.startDetectionLoop();
                },

                async startCamera() {
                    const video = this.$refs.video;

                    if (!video) {
                        this.cameraStatus = 'Video Missing ❌';
                        return;
                    }

                    this.cameraStatus = 'Starting...';

                    try {
                        this.videoStream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                width: 160,
                                height: 160,
                                facingMode: 'user',
                            },
                        });

                        video.srcObject = this.videoStream;

                        await video.play();

                        this.cameraStatus = 'Live';
                    } catch (error) {
                        console.error(error);

                        this.cameraStatus = 'Blocked ❌';
                        this.resultText = 'Camera Blocked';
                    }
                },

                startDetectionLoop() {
                    if (this.isDetecting) {
                        return;
                    }

                    this.isDetecting = true;

                    this.detectLoop();
                },

                async detectLoop() {
                    if (this.isMatched) {
                        this.isDetecting = false;
                        return;
                    }

                    if (!this.referenceDescriptor || !this.$refs.video) {
                        requestAnimationFrame(() => this.detectLoop());
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            this.$refs.video,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.cameraStatus = 'Scanning...';
                        this.resultText = 'No Face';

                        requestAnimationFrame(() => this.detectLoop());
                        return;
                    }

                    const distance = faceapi.euclideanDistance(
                        this.referenceDescriptor,
                        detection.descriptor
                    );

                    this.distanceText = `Distance: ${distance.toFixed(4)}`;

                    if (distance <= this.matchThreshold) {
                        this.isMatched = true;
                        this.cameraStatus = 'Match ✅';
                        this.resultText = 'Matched';

                        this.stopCamera();

                        if (this.$wire?.faceMatched) {
                            this.$wire.faceMatched();
                        }

                        return;
                    }

                    this.cameraStatus = 'Not Match';
                    this.resultText = 'Scanning';

                    requestAnimationFrame(() => this.detectLoop());
                },

                stopCamera() {
                    if (!this.videoStream) {
                        return;
                    }

                    this.videoStream.getTracks().forEach((track) => {
                        track.stop();
                    });

                    this.videoStream = null;

                    if (this.$refs.video) {
                        this.$refs.video.pause();
                        this.$refs.video.srcObject = null;
                    }

                    this.cameraStatus = 'Stopped';
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
use Filament\Infolists\Components\ImageEntry;
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
                    Wizard\Step::make('Password')
                        ->icon('bites-password')
                        ->afterValidation(function (): void {
                            $this->validatePasswordFactor();
                        })
                        ->schema([
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getRememberFormComponent(),
                            // Webcam::make('captured_image'),
                        ]),

                    Wizard\Step::make('Face ID')
                        ->icon('bites-face-scan')
                        ->schema([
                            TextInput::make('staff_no')
                                ->hidden(),
                            ImageEntry::make('header_image')
                                ->hiddenLabel()
                                ->defaultImageUrl(url('/pic/153582'))
                                ->imageHeight(40)
                                ->circular(),
                            FaceAuth::make('face')
                                ->staffNo(fn ($livewire) => $livewire->data['staff_no'] ?? null)
                                ->live(),
                        ]),
                ])
                    ->skippable(false),
                // ->submitAction(new HtmlString('
                //     <button type="submit" class="fi-btn fi-btn-size-md fi-color-primary">
                //         Sign in
                //     </button>
                // ')),
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

        Auth::login(
            $user,
            (bool) ($data['remember'] ?? false),
        );
        /*
         * If user has no staff record or no staff number:
         * - login immediately
         * - redirect to user panel
         * - stop wizard from proceeding to Step 2
         */
        if (! $user->staff || blank($user->staff->staff_no)) {
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
        session()->regenerate();
        $this->redirect(
            filament()->getPanel('lobby')->getUrl()
        );
    }

    protected function redirectToStaffPanel(): void
    {
        session()->regenerate();
        $this->redirect(
            filament()->getPanel('staff')->getUrl()
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

    public function faceMatched(): void
    {
        $this->redirectToStaffPanel();
    }
}

```

---

## File: `src\Models\UserAuth.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Who\src\Models\UserAuth.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Who\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'two_factor_secret',
    'two_factor_recovery_codes',
    'two_factor_confirmed_at',
    'face_descriptor',
    'setup_completed',
    'is_admin',
    'is_staff',
    'last_login',
    'last_face_auth',
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
            'last_login' => 'datetime',
            'last_face_auth' => 'datetime',
            'setup_completed' => 'boolean',
            'is_admin' => 'boolean',
            'is_staff' => 'boolean',
        ];
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
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected string $viewsPath = __DIR__.'/../resources/views';

    protected string $iconsPath = __DIR__.'/../resources/svg';

    protected function bootPackage(): void
    {
        // dd(app('view')->getFinder()->getHints());
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

