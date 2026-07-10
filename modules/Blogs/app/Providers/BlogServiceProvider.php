<?php

namespace Modules\Blogs\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'blog');

        Route::middleware('web')->as('blogs.')->group(__DIR__.'/../../routes/web.php');
    }
}
