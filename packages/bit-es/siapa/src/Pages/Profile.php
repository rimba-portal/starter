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
    protected static string $view = 'bites-identity::profile';

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
