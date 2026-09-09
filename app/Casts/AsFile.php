<?php

declare(strict_types=1);

namespace App\Casts;

use App\Traits\Models\HasFiles;
use App\ValueObjects\File;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsFile implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model&HasFiles  $model
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?File
    {
        return $value ? File::make($value, $model->diskName() ?? 'public') : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model&HasFiles  $model
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value instanceof File ? $value->relativePath() : $value;
    }
}
