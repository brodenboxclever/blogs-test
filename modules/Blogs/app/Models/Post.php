<?php

namespace Modules\Blogs\Models;

use App\Casts\AsFile;
use App\Traits\Models\HasNonPrimaryUuid;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('blog_posts')]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use HasNonPrimaryUuid;
    use Prunable, SoftDeletes;

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_indexable' => 'boolean',
        'is_visible_in_blog' => 'boolean',
        'is_comments_enabled' => 'boolean',
        'published_at' => 'datetime',
        'unpublished_at' => 'datetime',
        'image' => AsFile::class,
    ];

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::onlyTrashed()->where('deleted_at', '<=', a_month_ago());
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
