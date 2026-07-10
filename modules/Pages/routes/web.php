<?php

use Illuminate\Support\Facades\Route;
use Modules\Pages\Http\Controllers\PageController;

Route::middleware('auth')->group(function (): void {
    Route::resource('page', PageController::class);
});
