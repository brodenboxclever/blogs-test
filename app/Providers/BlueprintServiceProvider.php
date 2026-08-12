<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blueprint::macro('openGraphs', function () {
            $this->string('og_title')->nullable();
            $this->string('og_description')->nullable();
            $this->string('og_image')->nullable();
            $this->string('og_image_alt')->nullable();
        });

        Blueprint::macro('meta', function () {
            $this->string('meta_title')->nullable();
            $this->string('meta_description')->nullable();
        });

        Blueprint::macro('clientSession', function () {
            $this->string('ip_address')->nullable();
            $this->string('user_agent')->nullable()->comment('Text sent by browsers or scripts to identify the client\'s software, browser, or OS.');
            $this->string('referrer')->nullable()->comment('The absolute or partial address from which a resource has been requested');
            $this->string('phpsessid')->nullable()->comment('A hash used to identify the client\'s browser session.');

        });

        Blueprint::macro('slug', function (string $column = 'slug') {
            return $this->string('slug');
        });

        Blueprint::macro('image', function (string $column = 'image') {
            $image = $this->string($column);
            $this->string($column.'_alt')->nullable();

            return $image;
        });

        Blueprint::macro('readonly', function () {
            $this->boolean('is_readonly')->default(false)->comment('Whether the record is prevented from any further updates.');
            $this->foreignIdFor(User::class, 'readonly_by')->nullable()->comment('The user who marked the record as readonly.');
            $this->string('readonly_at')->nullable()->comment('The datetime when the record was marked as readonly.');
            $this->string('readonly_reason')->nullable();
        });

        Blueprint::macro('order', function ($column = 'order') {
            return $this->tinyInteger($column, unsigned: true)->nullable();
        });
    }
}
