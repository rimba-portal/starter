<?php

namespace App\Trees\Calendar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'org_unit_id',
        'org_team_id',
        'staff_id',
        'type',
        'name',
        'description',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
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
            'org_unit_id' => 'integer',
            'org_team_id' => 'integer',
            'staff_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgUnit::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgTeam::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }
}
