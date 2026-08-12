<?php

namespace Modules\Blogs\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table('blog_post_comments')]
class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;
}
