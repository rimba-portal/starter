<?php

namespace App\Trees\Sync\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiData extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'api_config_id',
        'fingerprint',
        'payload',
        'status',
        'processed_at',
        'error',
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
            'api_config_id' => 'integer',
            'payload' => 'array',
            'processed_at' => 'timestamp',
        ];
    }

    public function apiConfig(): BelongsTo
    {
        return $this->belongsTo(ApiConfig::class);
    }
}
