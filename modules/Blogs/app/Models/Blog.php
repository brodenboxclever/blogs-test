<?php

namespace Modules\Blogs\Models;

use App\Concerns\Traits\Models\HasNonPrimaryUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blogs\Database\Factories\BlogFactory;
use Modules\Pages\Models\Page;

class Blog extends Model
{
    /** @use HasFactory<BlogFactory> */
    use HasFactory;

    use HasNonPrimaryUuid;
    use Prunable, SoftDeletes;

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::onlyTrashed()->where('deleted_at', '<=', a_month_ago());
    }
}
