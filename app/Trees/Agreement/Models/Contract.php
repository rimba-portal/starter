<?php

namespace App\Trees\Agreement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contract extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'contract_type_id',
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
            'contract_type_id' => 'integer',
            'org_corp_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'renewal_date' => 'date',
            'terms' => 'array',
            'meta' => 'array',
        ];
    }

    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }

    public function contractConfidentiality(): HasOne
    {
        return $this->hasOne(\App\Business\Lcs\Models\ContractConfidentiality::class);
    }

    public function contractParties(): HasMany
    {
        return $this->hasMany(ContractParty::class);
    }

    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgCorp::class);
    }
}
