<?php

declare(strict_types=1);

namespace App\Trees\Organization\Models;

use App\Business\Tos\Models\Request;
use App\Models\User;
use App\Trees\AuditTrail\Models\AuditLog;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'user_id',
    'org_corp_id',
    'org_unit_id',
    'job_contract_id',
    'type',
    'status',
    'name',
    'staff_no',
    'attributes',
])]
class Staff extends Model
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
            'user_id' => 'integer',
            'org_corp_id' => 'integer',
            'org_unit_id' => 'integer',
            'job_contract_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function staffPositions(): HasMany
    {
        return $this->hasMany(StaffPosition::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function models(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'modelable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function jobContract(): BelongsTo
    {
        return $this->belongsTo(JobContract::class);
    }
}
