<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** @mixin Model */
trait HasCategories
{
    /**
     * Get the class name of the model category.
     */
    public function getCategoryModelClass(): string
    {
        if (property_exists($this, 'categoryModel')) {
            return $this->categoryModel;
        }

        $baseClass = class_basename(static::class);

        return "App\\Models\\{$baseClass}Category";
    }

    /** Get the table associated with the model's categories. */
    public function getCategoryTable(): string
    {
        if (property_exists($this, 'categoryTableName')) {
            return $this->categoryTableName;
        }

        return Str::singular($this->getTable()).'_categories';
    }

    public function category()
    {
        return $this->belongsTo(
            $this->getCategoryModelClass(),
            $this->getCategoryTable()
        );
    }
}
