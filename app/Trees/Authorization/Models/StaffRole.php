<?php

namespace App\Trees\Authorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffRole extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id',
        'role_id',
        'org_corp_id',
        'org_unit_id',
        'org_team_id',
        'job_position_id',
        'job_role_id',
        'job_contract_id',
        'source',
        'status',
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
            'staff_id' => 'integer',
            'role_id' => 'integer',
            'org_corp_id' => 'integer',
            'org_unit_id' => 'integer',
            'org_team_id' => 'integer',
            'job_position_id' => 'integer',
            'job_role_id' => 'integer',
            'job_contract_id' => 'integer',
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

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\JobPosition::class);
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\JobRole::class);
    }

    public function jobContract(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\JobContract::class);
    }
}
