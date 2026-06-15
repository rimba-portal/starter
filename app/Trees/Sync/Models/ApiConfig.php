<?php

namespace App\Trees\Sync\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiConfig extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'source_type',
        'source_config',
        'data_path',
        'depends_on',
        'mapping',
        'active',
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
            'source_config' => 'array',
            'depends_on' => 'array',
            'mapping' => 'array',
            'active' => 'boolean',
        ];
    }

    public function apiDatas(): HasMany
    {
        return $this->hasMany(ApiData::class);
    }
}
