<?php

namespace App\Providers;

use App\Faker\Provider\Html;
use Faker\Generator;
use Faker\Provider\Image;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (class_exists(Generator::class)) {
            $this->app->extend(Generator::class, function (Generator $generator, $app) {
                fake()->addProvider(new Html(fake()));
                fake()->addProvider(new Image(fake()));

                return $generator;
            });
        }
    }
}
