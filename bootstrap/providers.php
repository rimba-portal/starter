<?php

use App\Http\UI\Admin\AdminPanelProvider;
use App\Http\UI\Staff\StaffPanelProvider;
use App\Providers\AppServiceProvider;

return [
    AdminPanelProvider::class,
    StaffPanelProvider::class,
    AppServiceProvider::class,
];
