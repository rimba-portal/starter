<?php

declare(strict_types=1);

namespace App\Trees\Authorization\Models;

use App\Trees\Organization\Models\JobContract;
use App\Trees\Organization\Models\JobPosition;
use App\Trees\Organization\Models\JobRole;
use App\Trees\Organization\Models\OrgCorp;
use App\Trees\Organization\Models\OrgTeam;
use App\Trees\Organization\Models\OrgUnit;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

#[Fillable([
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
])]
class StaffRole extends Model
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
        return $this->belongsTo(Role::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function jobContract(): BelongsTo
    {
        return $this->belongsTo(JobContract::class);
    }
}
