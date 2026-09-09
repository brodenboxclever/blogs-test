<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

/** @mixin Model */
trait HasTags
{
    /**
     * Get the class name of the model tag.
     */
    public function getTagModelClass(): string
    {
        if (property_exists($this, 'tagModel')) {
            return $this->tagModel;
        }

        $baseClass = class_basename(static::class);

        return "App\\Models\\{$baseClass}Tag";
    }

    /** Get the table associated with the model's tags. */
    public function getTagTable(): string
    {
        if (property_exists($this, 'tagTableName')) {
            return $this->tagTableName;
        }

        return $this->getTable().'_tags';
    }

    public function tags()
    {
        return $this->hasMany(
            $this->getTagModelClass(),
            $this->getTagTable()
        );
    }
}
