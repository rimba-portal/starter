<?php

declare(strict_types=1);

namespace App\Trees\Organization\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'uuid',
    'job_position_id',
    'staff_id',
    'issuing_org_corp_id',
    'contract_type',
    'start_date',
    'end_date',
])]
class JobContract extends Model
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
            'job_position_id' => 'integer',
            'staff_id' => 'integer',
            'issuing_org_corp_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function orgCorp(): HasOne
    {
        return $this->hasOne(OrgCorp::class);
    }

    public function jobPositions(): HasMany
    {
        return $this->hasMany(JobPosition::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function issuingOrgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}
