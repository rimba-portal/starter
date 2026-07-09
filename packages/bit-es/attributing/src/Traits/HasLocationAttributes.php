<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Bites\Attributing\Models\LocationAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLocationAttributes
{
    /**
     * @property Collection $LocationAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany LocationAttributes()
     */
    public function LocationAttributes(): MorphMany
    {
        return $this->morphMany(LocationAttribute::class, 'attributable');
    }
}
