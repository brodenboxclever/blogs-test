<?php

namespace Modules\Blogs\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table('blog_posts')]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;
}
