<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'offer_id',
    'offer_category_id',
    'attributes',
])]
class OfferCategoryAssignment extends Model
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
            'offer_id' => 'integer',
            'offer_category_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function offerCategory(): BelongsTo
    {
        return $this->belongsTo(OfferCategory::class);
    }
}
