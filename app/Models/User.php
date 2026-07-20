<?php

declare(strict_types=1);

namespace App\Models;

use App\Trees\Organization\Models\Staff;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory;
    use Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@rimba.com');
        }

        return true;
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $number = $this->staff?->staff_no;

        // Always have a safe default
        $default = asset('images/unknown_user.png');

        if (! $number) {
            return $default;
        }

        $url = sprintf('http://10.40.3.41:8080/%s.jpg', $number);

        try {
            // Lightweight check without downloading the file body
            $response = Http::timeout(1.5)->head($url);

            if ($response->ok()) {
                return $url;
            }
        } catch (\Throwable $throwable) {
            // Swallow network/timeout errors and fall back
        }

        return $default;
    }

    // Add this method inside your existing App\Models\User class

    public function getActor(): string
    {
        // Start with the user's name
        $identifier = $this->name;

        // Check if staff relationship exists safely
        if ($this->staff) {
            $parts = [];

            if ($this->staff->staff_no) {
                $parts[] = $this->staff->staff_no;
            }

            // Adjust 'job_title' or 'position' to match your actual Staff table column
            if ($this->staff->job_title) {
                $parts[] = 'as '.$this->staff->job_title;
            }

            if ($parts !== []) {
                // Combines into: " [staff_no as job_title]" or " [staff_no]" or " [as job_title]"
                $identifier .= ' ['.implode(' ', $parts).']';
            }
        }

        return $identifier;
    }
}
