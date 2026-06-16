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
    'user_id',
])]
class VersionApproval extends Model
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
            'user_id' => 'integer',
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
