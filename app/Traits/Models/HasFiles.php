<?php

declare(strict_types=1);

namespace App\Traits\Models;

use App\Casts\AsFile;
use App\Jobs\DeleteFileJob;
use App\Objects\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * This trait handles cleanup for any files attached to the model.
 *
 * This trait will clean up files as the model is deleted or replaced. Files are detected via the
 * `AsFiles` cast.
 *
 * @mixin Model
 */
trait HasFiles
{
    protected static string $disk_name = 'public';

    public static function bootHasFiles(): void
    {
        static::deleted(function (Model $model) {
            $model->queueFilesForDeletion();
        });

        static::updating(function (Model $model) {
            $model->queueReplacedFilesForDeletion();
        });
    }

    final public static function diskName(): string
    {
        return static::$disk_name;
    }

    final public static function storageDisk(): Filesystem
    {
        return Storage::disk(static::$disk_name);
    }

    /**
     * The name of the directory to save files into, like: `/model/file.png`.
     */
    public static function fileDirectory(): string
    {
        return strtolower(class_basename(static::class));
    }

    /**
     * Get a subset of the model's attributes which are cast as files.
     *
     * @return array<string, File>
     */
    public function getFileAttributes(): array
    {
        return $this->only(
            collect($this->getCasts())
                ->filter(fn ($cast) => $cast === AsFile::class)
                ->keys()
                ->all()
        );
    }

    /**
     * Dispatch a job to soft-delete any files which have been replaced in the model.
     */
    private function queueReplacedFilesForDeletion(bool $soft_delete = true): void
    {
        $file_attributes = array_keys($this->getFileAttributes());

        // If the attribute is dirty (has changed), queue it for deletion.
        foreach ($file_attributes as $attribute) {
            if ($this->isDirty($attribute)) {
                $old_file = $this->getOriginal($attribute);

                if ($old_file instanceof File && $old_file->exists()) {
                    DeleteFileJob::dispatch($old_file->disk(), $old_file->relativePath(), $soft_delete)->afterCommit();
                }
            }
        }
    }

    /**
     * Dispatch a job to soft-delete all files attached to the model.
     */
    private function queueFilesForDeletion(bool $soft_delete = true): void
    {
        $files = $this->getFileAttributes();

        // If the file exists, queue it for deletion.
        foreach ($files as $file) {
            if ($file instanceof File && $file->exists()) {
                DeleteFileJob::dispatch($file->disk(), $file->relativePath(), $soft_delete)->afterCommit();
            }
        }
    }
}
