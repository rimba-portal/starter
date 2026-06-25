<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'attributable_id',
        'attributable_type',
    ];

    public function attributable()
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'value' => 'encrypted',
        ];
    }
}