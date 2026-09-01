<?php

namespace App\Providers\Database\Schema;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint as BaseBlueprint;
use Illuminate\Database\Schema\ColumnDefinition;

class Blueprint extends BaseBlueprint
{
    /**
     * Add OpenGraph meta tags columns to the table.
     */
    public function openGraphs(): void
    {
        $this->string('og_title')->nullable();
        $this->string('og_description')->nullable();
        $this->string('og_image')->nullable();
        $this->string('og_image_alt')->nullable();
    }

    /**
     * Add general SEO meta title and description columns.
     */
    public function meta(): void
    {
        $this->string('meta_title')->nullable();
        $this->string('meta_description')->nullable();
    }

    /**
     * Add tracking columns for client session details.
     */
    public function clientSession(): void
    {
        $this->string('ip_address')->nullable();
        $this->string('user_agent')->nullable()->comment('Text sent by browsers or scripts to identify the client\'s software, browser, or OS.');
        $this->string('referrer')->nullable()->comment('The absolute or partial address from which a resource has been requested');
        $this->string('phpsessid')->nullable()->comment('A hash used to identify the client\'s browser session.');
    }

    /**
     * Add a slug column to the table.
     */
    public function slug(string $column = 'slug'): ColumnDefinition
    {
        return $this->string($column);
    }

    /**
     * Add an image string column along with a corresponding nullable alt text column.
     */
    public function image(string $column = 'image', bool $includeAltText = true): ColumnDefinition
    {
        $image = $this->string($column);
        if ($includeAltText) {
            $this->string($column.'_alt')->nullable();
        }

        return $image;
    }

    /**
     * Add audit columns to mark a record as read-only.
     */
    public function readonly(): void
    {
        $this->boolean('is_readonly')->default(true)->comment('Whether the record is prevented from any further updates.');
        $this->foreignIdFor(User::class, 'readonly_by')->nullable()->comment('The user who marked the record as readonly.');
        $this->timestamp('readonly_at')->nullable()->comment('The datetime when the record was marked as readonly.');
        $this->string('readonly_reason')->nullable();
    }

    /**
     * Add an unsigned tinyInteger column for sorting purposes.
     *
     * @param  string  $column  Column name, defaults to 'order'
     */
    public function order(string $column = 'order'): ColumnDefinition
    {
        return $this->tinyInteger($column, unsigned: true)->nullable();
    }
}
