<?php

namespace Bites\Identity\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Profile extends Page
{
    protected static string $view = 'bites-identity::profile';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;
    protected static ?string $title = 'Account Setup';

    public function mount()
    {
        abort_unless(Auth::check(), 403);
    }

    public function getActions(): array
    {
        $user = Auth::user();
        return [
            Action::make('setup_face')
                ->label($user->face_descriptor ? '✅ Face Verified' : '📸 Set Up Face')
                ->color($user->face_descriptor ? 'success' : 'primary')
                ->disabled(filled($user->face_descriptor))
                ->action(fn() => $this->dispatch('open-face-setup-modal')),
            Action::make('setup_totp')
                ->label($user->hasConfirmedTwoFactorAuthentication() ? '✅ TOTP Active' : '🔑 Set Up TOTP')
                ->color($user->hasConfirmedTwoFactorAuthentication() ? 'success' : 'primary')
                ->url(fn() => route('filament.admin.pages.profile.mfa')),
            Action::make('mark_complete')
                ->label('✅ Complete Setup')
                ->color('success')
                ->visible(fn() => !$user->auth->setup_completed)
                ->disabled(fn() => !filled($user->face_descriptor) || !$user->hasConfirmedTwoFactorAuthentication())
                ->action(function () use ($user) {
                    $user->auth->update(['setup_completed' => true]);
                    Notification::make()->success()->title('Setup complete!')->send();
                })
        ];
    }
}
