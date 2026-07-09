<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'family',
    'group',
    'key',
    'name',
    'description',
    'applies_to',
    'example_key',
    'example_value',
    'has_options',
    'is_active',
    'is_abac',
    'is_system',
    'sort_order',
])]
class AttributeDefinition extends Model
{
    protected function casts(): array
    {
        return [
            'applies_to' => 'array',
            'has_options' => 'boolean',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}
