<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use Modules\Blogs\Providers\BlogServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    BlogServiceProvider::class,
];
