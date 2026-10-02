<?php

use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Modules\Blogs\Http\Controllers\BlogController;
use Modules\Blogs\Http\Controllers\PostController;

Route::middleware('auth')->group(function (): void {
    Route::resource('blogs', BlogController::class)
        ->middleware([HandlePrecognitiveRequests::class]);
    Route::resource('blogs.posts', PostController::class)
        ->middleware([HandlePrecognitiveRequests::class]);
})->missing(function (Request $request) {
    return Redirect::route('blogs::blogs.index')->withErrors('The requested item could not be found.');
});
