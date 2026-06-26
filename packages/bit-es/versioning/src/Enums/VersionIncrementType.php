<?php

declare(strict_types=1);

namespace Bites\Versioning\Enums;

enum VersionIncrementType: string
{
    case Major = 'major';
    case Minor = 'minor';
    case Patch = 'patch';
}
