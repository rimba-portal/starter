<?php

declare(strict_types=1);

namespace App\Business\Hrm\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'jobgrade',
    'uuid',
    'description',
    'attributes',
    'masco_code',
])]
class JobTitle extends Model
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
            'attributes' => 'array',
        ];
    }
}
