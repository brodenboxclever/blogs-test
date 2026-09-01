<?php

use Illuminate\Support\Facades\Route;
use Modules\Blogs\Http\Controllers\BlogController;
use Modules\Blogs\Http\Controllers\PostController;

Route::middleware('auth')->group(function (): void {
    Route::resource('blog', BlogController::class);
    Route::resource('blog.post', PostController::class);
})->missing(function (Request $request) {
    return Redirect::route('blogs.blog.index')->withErrors('The requested item could not be found.');
});
