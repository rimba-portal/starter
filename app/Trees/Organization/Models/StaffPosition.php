<?php

declare(strict_types=1);

namespace App\Trees\Organization\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'staff_id',
    'job_position_id',
    'assignment_type',
    'status',
    'start_date',
    'end_date',
    'attributes',
])]
class StaffPosition extends Model
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
            'job_position_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }
}
