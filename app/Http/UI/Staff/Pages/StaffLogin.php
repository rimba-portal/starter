<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use App\Trees\Authentication\Filament\Components\FaceVerification;
use App\Trees\Organization\Models\Staff;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;

class StaffLogin extends Page implements HasSchemas
{
    // use InteractsWithActions;
    use InteractsWithSchemas;

    protected string $view = 'staff.auth.auth-login';

    public ?string $staffId = null;

    public ?Staff $staff = null;

    public bool $faceVerified = false;

    public string $pin = '';

    public bool $pinVerified = false;

    public function form(Schema $schema): Schema
    {

        return $schema
            ->components([
                Wizard::make([
                    Step::make('Staff ID')
                        ->schema([
                            TextInput::make('staffId')
                                ->label('Staff ID')
                                ->required(),
                        ])
                        ->afterValidation(function (): void {

                            logger('startFaceRecognition called');
                            $this->staff = Staff::where('staff_no', '=', $this->staffId, 'and')->first();
                            logger([
                                'staffId' => $this->staffId,
                                'staff' => $this->staff,
                            ]);
                            if (! $this->staff instanceof Staff) {
                                $this->dispatch(
                                    'face-error',
                                    message: 'Staff not found'
                                );

                                return;
                            }

                            $this->startFaceRecognition();
                        }),

                    Step::make('Face')
                        ->schema([
                            FaceVerification::make('faceVerification'),
                        ])
                        ->afterValidation(function (): void {

                            if (! $this->faceVerified) {

                                Notification::make()
                                    ->title('Face verification required')
                                    ->danger()
                                    ->send();

                                throw ValidationException::withMessages([
                                    'faceVerification' => 'Please verify your face.',
                                ]);
                            }
                        }),

                    Step::make('Pin')
                        ->schema([
                            TextInput::make('pin')
                                ->password()
                                ->required(),
                        ]),
                    // ])->extraAlpineAttributes([
                    //     // Listens for your Livewire dispatch and forces the Alpine wizard forward
                    //     '@face-verification-success.window' => 'next()',

                ]),
            ]);
    }

    public function startFaceRecognition(): void
    {
        $this->dispatch(
            'start-face-recognition',
            photo: '/pic/'.$this->staffId
        );
    }

    #[On('faceMatched')]
    public function faceMatched(): void
    {
        $this->faceVerified = true;
        $this->dispatch('face-verification-success');
        Notification::make()
            ->title('Face verified')
            ->success()
            ->send();
    }
}
