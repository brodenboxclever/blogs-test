<?php

use App\Providers\AppServiceProvider;
use App\Providers\BlueprintServiceProvider;
use App\Providers\FortifyServiceProvider;
use Modules\Blogs\Providers\BlogServiceProvider;
use Modules\Pages\Providers\PageServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    BlueprintServiceProvider::class,
    PageServiceProvider::class,
    BlogServiceProvider::class,
];
