<?php

namespace App\Trees\Organization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Movement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'effective_date',
        'from',
        'to',
    ];

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
