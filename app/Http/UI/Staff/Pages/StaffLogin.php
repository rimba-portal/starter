<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\On;

class StaffLogin extends Page implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected string $view = 'staff.auth.staff-login';

    public ?string $staffId = null;

    public ?User $staff = null;

    public bool $faceVerified = false;

    public bool $pinVerified = false;

    public string $pin = '';

    public function form(Schema $schema): Schema
    {

        return $schema
            ->components([

                TextInput::make('staffId')
                    ->label('Staff ID')
                    ->required(),
                Action::make('scan')
                    ->icon('heroicon-o-camera')
                    ->action('startVerification'),

            ]);
    }

    public function startFaceRecognition(): void
    {
        User::where(
            'staff_id',
            '=',
            $this->staffId,
            'and'
        )->first();

        if (! $this->staff instanceof User) {

            $this->dispatch(
                'face-error',
                message: 'Staff not found'
            );

            return;
        }

        $this->dispatch(
            'start-face-recognition',
            photo: '/pic/'.$this->staffId
        );
    }

    #[On('faceMatched')]
    public function faceMatched(): void
    {
        $this->faceVerified = true;
        dd('Face matched');
    }
}
