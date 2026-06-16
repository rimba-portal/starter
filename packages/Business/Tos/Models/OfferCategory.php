<?php

declare(strict_types=1);

namespace App\Business\Tos\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id',
    'name',
    'description',
    'attributes',
])]
class OfferCategory extends Model
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
            'parent_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(OfferCategory::class);
    }

    public function offerCategoryAssignments(): HasMany
    {
        return $this->hasMany(OfferCategoryAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OfferCategory::class);
    }
}
