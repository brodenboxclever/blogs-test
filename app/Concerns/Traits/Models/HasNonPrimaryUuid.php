<?php

namespace App\Concerns\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Enables auto-generating UUIDs on model creation while still allowing a numeric incrementing primary key, which is
 * better for indexing.
 *
 * @mixin Model
 */
trait HasNonPrimaryUuid
{
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function bootHasNonPrimaryUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid7();
            }
        });
    }
}
