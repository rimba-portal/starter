<?php

declare(strict_types=1);

return [

    'navigation_group' => 'Settings',

    'navigation_sort' => 90,

    'families' => [
        'personnel' => 'Personnel',
        'asset' => 'Asset',
        'area' => 'Area',
    ],

    'groups' => [
        'personnel' => [
            'identity' => 'Identity',
            'organization' => 'Organization',
            'skills' => 'Skills',
            'qualification' => 'Qualification',
            'security' => 'Security',
            'requirements' => 'Requirements',
            'health' => 'Health',
        ],

        'asset' => [
            'identification' => 'Identification',
            'classification' => 'Classification',
            'technical' => 'Technical',
            'lifecycle' => 'Lifecycle',
            'maintenance' => 'Maintenance',
            'document' => 'Document',
        ],

        'area' => [
            'geography' => 'Geography',
            'enterprise' => 'Enterprise',
            'facility' => 'Facility',
            'operations' => 'Operations',
            'security' => 'Security',
            'environment' => 'Environment',
            'emergency' => 'Emergency',
        ],
    ],

];
