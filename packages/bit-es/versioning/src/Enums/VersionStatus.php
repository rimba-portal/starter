<?php

declare(strict_types=1);

namespace Bites\Versioning\Enums;

enum VersionStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Approved = 'approved';
    case Released = 'released';
    case Obsolete = 'obsolete';
    case Archived = 'archived';
}
