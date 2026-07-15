<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\LecturerPanelProvider;
use App\Providers\Filament\StudentPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    LecturerPanelProvider::class,
    StudentPanelProvider::class,
];
