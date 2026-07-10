<?php

use Illuminate\Support\Facades\Route;
use Modules\Blogs\Http\Controllers\BlogController;

Route::middleware('auth')->group(function (): void {
    Route::resource('blog', BlogController::class);
});
