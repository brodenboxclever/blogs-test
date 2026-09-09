<?php

namespace Modules\Blogs\Models;

use App\Traits\Models\HasNonPrimaryUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blogs\Database\Factories\BlogFactory;

class Blog extends Model
{
    /** @use HasFactory<BlogFactory> */
    use HasFactory;

    use HasNonPrimaryUuid;
    use Prunable, SoftDeletes;

    protected $fillable = ['is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_readonly' => 'boolean',
    ];

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::onlyTrashed()->where('deleted_at', '<=', a_month_ago());
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
