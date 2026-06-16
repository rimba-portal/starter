<?php

declare(strict_types=1);

namespace App\Trees\Organization\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'type',
    'effective_date',
    'from',
    'to',
])]
class Movement extends Model
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
            'effective_date' => 'date',
            'from' => 'array',
            'to' => 'array',
        ];
    }

    public function movable(): MorphTo
    {
        return $this->morphTo();
    }
}
