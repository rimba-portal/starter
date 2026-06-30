<?php

use Bites\Identity\Http\Controllers\IdentityController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->post('/profile/save-face', [IdentityController::class, 'saveFace'])->name('profile.save-face');