<?php

declare(strict_types=1);

return [
    'sync' => ['queue' => false],
    'ui' => [
        'packages' => [
            'app/Filament' => 'App\Filament',
        ],
        'panels' => [
            // panel_id, path, color, brandName, homeUrl
            'admin' => ['admin', 'admin', '#7f174b', 'Administrator Portal', 'filament.staff.pages.dashboard'],
            'staff' => ['staff', 'staff', '#09829f', 'ATM Staff Intranet', 'filament.staff.pages.dashboard'],
        ],
        'navigation_groups' => [
            'staff' => [
                'tasks' => ['title' => 'Tasks', 'tagline' => 'Need to do something?'],
                'help' => ['title' => 'Help', 'tagline' => 'Need guidance?'],
                'explore' => ['title' => 'Explore', 'tagline' => 'Need information?'],
                'requests' => ['title' => 'Requests', 'tagline' => 'Need a service?'],
                'improve' => ['title' => 'Improve', 'tagline' => 'Need to learn?'],
                'menu' => ['title' => 'Menu', 'tagline' => 'Navigate to?'],
                'broadcast' => ['title' => 'Broadcast', 'tagline' => 'Get company updates?'],
                'accountables' => ['title' => 'Accountables', 'tagline' => 'Need to know what you own?'],
            ],

        ],

    ],
    'unit_roles' => ['owner', 'member'],
    'team_roles' => ['captain', 'scout', 'player', 'quartermaster', 'tactician', 'coach'],
];
