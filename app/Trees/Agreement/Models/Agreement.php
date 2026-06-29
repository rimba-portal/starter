<?php

declare(strict_types=1);

namespace App\Trees\Agreement\Models;

use App\Trees\Organization\Models\OrgCorp;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'uuid',
    'agreement_type',
    'org_corp_id',
    'contract_no',
    'title',
    'summary',
    'start_date',
    'end_date',
    'renewal_date',
    'status',
    'terms',
    'meta',
])]
class Agreement extends Model
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
            'org_corp_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'renewal_date' => 'date',
            'terms' => 'array',
            'meta' => 'array',
        ];
    }

    public function agreementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function agreementType(): BelongsTo
    {
        return $this->belongsTo(AgreementType::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}
