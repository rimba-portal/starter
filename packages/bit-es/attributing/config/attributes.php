<?php

declare(strict_types=1);

return [

    'navigation_group' => 'Settings',

    'navigation_sort' => 90,

    'families' => [
        'person' => 'Person',
        'thing' => 'Things',
        'location' => 'Locations',
    ],

    'groups' => [
        'person' => [
            'identity' => 'Identity',
            'organization' => 'Organization',
            'skills' => 'Skills',
            'qualification' => 'Qualification',
            'security' => 'Security',
            'requirements' => 'Requirements',
            'health' => 'Health',
        ],

        'thing' => [
            'identification' => 'Identification',
            'classification' => 'Classification',
            'technical' => 'Technical',
            'lifecycle' => 'Lifecycle',
            'maintenance' => 'Maintenance',
            'document' => 'Document',
        ],

        'location' => [
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
