<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use Modules\Blogs\Providers\BlogServiceProvider;
use Modules\Pages\Providers\PageServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    PageServiceProvider::class,
    BlogServiceProvider::class,
];
