<?php

namespace App\Business\Hrm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id',
        'org_corp_id',
        'status',
        'employee_no',
        'hire_date',
        'termination_date',
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
            'org_corp_id' => 'integer',
            'hire_date' => 'date',
            'termination_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\OrgCorp::class);
    }
}
