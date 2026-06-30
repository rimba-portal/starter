<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'asset_id',
    'type',
    'start_date',
    'end_date',
    'attributes',
])]
class AssetAssignment extends Model
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
            'asset_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
