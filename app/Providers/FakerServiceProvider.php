<?php

namespace App\Providers;

use App\Faker\Provider\Html;
use App\Faker\Provider\Image;
use Faker\Generator;
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
                $generator->addProvider(new Html($generator));
                $generator->addProvider(new Image($generator));

                return $generator;
            });
        }
    }
}
