<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Models;

use Bites\Service\Concerns\HasAttachableExtLink;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'category',
    'group',
    'name',
    'slug',
    'description',
    'icon',
    'color',
    'parent_id',
    'permission',
    'panel',

    'is_visible',
    'is_active',
    'sort',
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
