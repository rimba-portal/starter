<?php

declare(strict_types=1);

use Rimba\Identity\Factors\FaceFactor;
use Rimba\Identity\Factors\PinFactor;

return [

    'guard' => 'web',

    'staff_id_column' => 'staff_id',

    'face_threshold' => 0.50,

    'pipeline' => [
        'face',
        'pin',
    ],

    'drivers' => [

        'face' => FaceFactor::class,

        'pin' => PinFactor::class,

    ],

];
