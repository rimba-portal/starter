<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Bites\Attributing\Models\PlaceAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPlaceAttributes
{
    /**
     * @property Collection $placeAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany placeAttributes()
     */
    public function placeAttributes(): MorphMany
    {
        return $this->morphMany(PlaceAttribute::class, 'attributable');
    }
}
