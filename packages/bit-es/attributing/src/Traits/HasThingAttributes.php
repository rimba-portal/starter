<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Bites\Attributing\Models\ThingAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasThingAttributes
{
    /**
     * @property Collection $thingAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany thingAttributes()
     */
    public function thingAttributes(): MorphMany
    {
        return $this->morphMany(ThingAttribute::class, 'attributable');
    }
}
