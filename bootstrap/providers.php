<?php

use App\Http\UI\Admin\AdminPanelProvider;
use App\Http\UI\Lobby\LobbyPanelProvider;
use App\Http\UI\Staff\StaffPanelProvider;
use App\Providers\AppServiceProvider;

return [
    AdminPanelProvider::class,
    LobbyPanelProvider::class,
    StaffPanelProvider::class,
    AppServiceProvider::class,
];
