<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

/** @mixin Model */
trait HasMultiCategories
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

        return $this->getTable().'_categories';
    }

    public function categories()
    {
        return $this->belongsToMany(
            $this->getCategoryModelClass(),
            $this->getCategoryTable()
        );
    }
}
