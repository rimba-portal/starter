<?php

declare(strict_types=1);

namespace App\Trees\Catalog\Models;

use Bites\Service\Concerns\HasAttachableExtLink;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // use HasAttachableExtLink;
    use HasVersions;

    protected $guard_name = 'web';

    protected $fillable = [
        'category',
        'title',
        'icon',
        'icon_type',
        'description',
        'internal_link',
        // 'external_link',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => false,
    ];

}
