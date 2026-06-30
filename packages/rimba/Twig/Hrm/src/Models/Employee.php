<?php

declare(strict_types=1);

namespace Rimba\Twig\Hrm\Models;

use App\Trees\Organization\Models\OrgCorp;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'staff_id',
    'org_corp_id',
    'status',
    'employee_no',
    'hire_date',
    'termination_date',
    'attributes',
])]
class Employee extends Model
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
            'org_corp_id' => 'integer',
            'hire_date' => 'date',
            'termination_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}
