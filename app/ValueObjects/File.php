<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Stringable;

class File implements Stringable
{
    public readonly FilesystemAdapter|Filesystem $storage;

    private function __construct(
        protected readonly string $path,
        protected readonly string $disk,
    ) {
        $this->storage = Storage::disk($disk);
    }

    public static function make(string $path, string $disk): File
    {
        return new static($path, $disk);
    }

    public function disk(): string
    {
        return $this->disk;
    }

    public function relativePath(): string
    {
        return $this->path;
    }

    public function exists(): bool
    {
        return is_file($this->absolutePath());
    }

    /**
     * Path to the image from the storage directory.
     */
    public function assetPath(): string
    {
        return asset('storage/'.$this->path);
    }

    public function url(): string
    {
        return $this->storage->url($this->path);
    }

    public function size(): int
    {
        if (! $this->exists()) {
            throw new RuntimeException("File not found: {$this->path}");
        }

        return $this->storage->size($this->path);
    }

    public function mimeType(): string|false
    {
        if (! $this->exists()) {
            throw new RuntimeException("File not found: {$this->path}");
        }

        return $this->storage->mimeType($this->path);
    }

    public function lastModified(): Carbon
    {
        if (! $this->exists()) {
            throw new RuntimeException("File not found: {$this->path}");
        }

        return Carbon::createFromTimestamp($this->storage->lastModified($this->path));
    }

    public function absolutePath(): string
    {
        return $this->storage->path($this->path);
    }

    public function __toString(): string
    {
        return $this->assetPath();
    }
}
