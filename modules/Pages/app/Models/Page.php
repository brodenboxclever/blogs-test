<?php

namespace Modules\Pages\Models;

use App\Concerns\Traits\Models\HasNonPrimaryUuid;
use App\Traits\Models\HasTree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pages\Database\Factories\PageFactory;

class Page extends Model
{
    protected $fillable = ['parent_id', 'slug'];

    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use HasNonPrimaryUuid;
    use HasTree;
    use Prunable, SoftDeletes;

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::onlyTrashed()->where('deleted_at', '<=', a_month_ago());
    }
}
