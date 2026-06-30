<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Models;

use Bites\Service\Concerns\HasAttachableExtLink;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'category',
    'title',
    'icon',
    'icon_type',
    'description',
    'internal_link',
    // 'external_link',
    'is_active',
])]
class Menu extends Model
{
    // use HasAttachableExtLink;
    use HasVersions;

    protected $guard_name = 'web';

    protected $attributes = [
        'is_active' => false,
    ];
}
