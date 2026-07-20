<?php

declare(strict_types=1);

namespace Bites\Versioning\Models;

use Bites\Versioning\Builders\VersionBuilder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'versionable_type',
    'versionable_id',
    'version',
    'major',
    'minor',
    'patch',
    'status',
    'content_type',
    'content_url',
    'checksum',
    'effective_from',
    'effective_until',
    'released_at',
    'notes',
])]
class Version extends Model
{
    public function newEloquentBuilder($query): VersionBuilder
    {
        return new VersionBuilder($query);
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
            'released_at' => 'datetime',
        ];
    }
    protected static function booted(): void
    {
        static::creating(function (Version $version) {
            dump('iscreating');
            // 1. If a user is logged in, use their rich identifier string
            if ($user = Auth::user()) {
                $version->upload_by = $user->getUploadIdentifier();
                return;
            }

            // 2. Fallback check: If already manually defined in the seeder, keep it
            if (!empty($version->upload_by)) {
                return;
            }

            // 3. Absolute fallback for system/seeder automation
            $version->upload_by = 'System';
        });
    }
}
