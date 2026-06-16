<?php

declare(strict_types=1);

namespace App\Trees\Copies\Models;

use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'version_id',
    'locked_by',
])]
class VersionLock extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'version_id' => 'integer',
            'locked_by' => 'integer',
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
