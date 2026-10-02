<?php

namespace Modules\Blogs\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Modules\Blogs\Models\Blog;

class UpsertBlog
{
    public function handle(array $attributes, Blog $idea): Blog
    {
        return $idea;
    }
}
