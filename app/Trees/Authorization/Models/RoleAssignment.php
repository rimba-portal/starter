<?php

namespace App\Trees\Authorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'staff_id',
        'assigned_by',
        'org_unit_id',
        'org_team_id',
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
            'role_id' => 'integer',
            'staff_id' => 'integer',
            'assigned_by' => 'integer',
            'org_unit_id' => 'integer',
            'org_team_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
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
