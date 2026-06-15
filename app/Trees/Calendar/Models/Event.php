<?php

namespace App\Trees\Calendar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'org_corp_id',
        'org_unit_id',
        'org_team_id',
        'type',
        'name',
        'description',
        'start_at',
        'end_at',
        'is_blocking',
        'attributes',
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
            'org_corp_id' => 'integer',
            'org_unit_id' => 'integer',
            'org_team_id' => 'integer',
            'start_at' => 'timestamp',
            'end_at' => 'timestamp',
            'is_blocking' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgCorp::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgUnit::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgTeam::class);
    }
}
