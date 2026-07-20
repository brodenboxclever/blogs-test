<?php

namespace Modules\Pages\Models;

use App\Concerns\Traits\Models\HasNonPrimaryUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pages\Database\Factories\PageFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use HasNonPrimaryUuid;
    use HasRecursiveRelationships;
    use Prunable, SoftDeletes;

    public function getPathName()
    {
        return '_path';
    }

    public function getCustomPaths()
    {
        return [
            [
                'name' => 'path',
                'column' => 'slug',
                'separator' => '/',
            ],
        ];
    }

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::onlyTrashed()->where('deleted_at', '<=', a_month_ago());
    }
}
