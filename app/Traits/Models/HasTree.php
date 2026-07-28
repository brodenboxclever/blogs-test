<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * A model trait which adds behaviours for tree-like relationships.
 *
 * Utilizes recursive CTE sql queries to fetch all tree nodes from top-level nodes.
 *
 * Computes a `path` attribute based off a slug key. Key names can be overridden using `getParentKeyName()` and `getSlugKeyName()`
 *
 * @mixin Model
 */
#[Hidden(['_cte_chain'])]
trait HasTree
{
    /**
     * Get the name of the slug key column to use for path generation.
     */
    public function getSlugKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the name of the parent key column.
     */
    public function getParentKeyName(): string
    {
        return 'parent_id';
    }

    /**
     * Hook into model event lifecycles to run validation to ensure the tree relations are valid.
     */
    protected static function bootHasTree(): void
    {
        static::saving(function (Model $model) {
            /** @var static $model */
            $parentKey = $model->getParentKeyName();
            $parentId = $model->getAttribute($parentKey);
            $modelId = $model->getKey();

            // Prevent self-parenting
            if ($parentId && $parentId == $modelId) {
                throw ValidationException::withMessages([
                    $parentKey => 'A model cannot be its own parent.',
                ]);
            }

            // Prevent circular references
            if ($parentId && $modelId && $model->isDescendantOf($parentId)) {
                throw ValidationException::withMessages([
                    $parentKey => 'Cannot assign a descendant as a parent (circular reference).',
                ]);
            }

            // Prevent duplicate slugs in siblings.
            $primaryKey = $model->getKeyName();
            $slugKeyName = $model->getSlugKeyName();
            $slugValue = $model->getAttribute($slugKeyName);

            $duplicateExists = static::query()
                ->where($parentKey, $parentId)
                ->where($slugKeyName, $slugValue)
                ->when($modelId, fn ($query) => $query->where($primaryKey, '!=', $modelId))
                ->exists();

            if ($duplicateExists) {
                $label = Str::of($slugKeyName)->headline()->lower();
                throw ValidationException::withMessages([
                    $slugKeyName => "A record with this {$label} already exists under the selected parent.",
                ]);
            }
        });
    }

    /**
     * Query the entire tree using a single recursive CTE statement.
     */
    public function scopeWithTree(Builder $query): Builder
    {
        $table = $this->getTable();
        $slugKey = $this->getSlugKeyName();
        $primaryKey = $this->getKeyName();
        $parentKey = $this->getParentKeyName();

        $cte = <<<SQL
            WITH RECURSIVE page_tree AS (
                -- Root level items
                SELECT
                    *,
                    CONCAT('/', CAST({$slugKey} AS CHAR(1000))) AS path,
                    CAST(CONCAT('/', {$primaryKey}, '/') AS CHAR(1000)) AS _cte_chain
                FROM {$table}
                WHERE {$parentKey} IS NULL

                UNION ALL

                -- Nested child items
                SELECT
                    p.*,
                    CONCAT(pt.path, '/', p.{$slugKey}) AS path,
                    CONCAT(pt._cte_chain, p.{$primaryKey}, '/') AS _cte_chain
                FROM {$table} p
                INNER JOIN page_tree pt ON p.{$parentKey} = pt.{$primaryKey}

                -- Stop recursion if child ID already exists in the chain
                WHERE INSTR(pt._cte_chain, CONCAT('/', p.{$primaryKey}, '/')) = 0
            )
        SQL;

        return $query
            ->fromRaw("({$cte} SELECT * FROM page_tree) as {$table}")
            ->select("{$table}.*");
    }

    /**
     * Query all descendants of a tree node using a single recursive CTE statement.
     */
    public function scopeDescendantsOf(Builder $query, int $parentId): Builder
    {
        $table = $this->getTable();
        $primaryKey = $this->getKeyName();
        $parentKey = $this->getParentKeyName();

        $cte = <<<SQL
            WITH RECURSIVE page_tree AS (
                -- Root parent item
                SELECT
                    *,
                    CONCAT('/', CAST(slug AS CHAR(1000))) AS path,
                    CAST(CONCAT('/', {$primaryKey}, '/') AS CHAR(1000)) AS _cte_chain
                FROM {$table}
                WHERE {$primaryKey} = {$parentId}

                UNION ALL

                -- Nested child items
                SELECT
                    p.*,
                    CONCAT(pt.path, '/', p.slug) AS path,
                    CONCAT(pt._cte_chain, p.{$primaryKey}, '/') AS _cte_chain
                FROM {$table} p
                INNER JOIN page_tree pt ON p.{$parentKey} = pt.{$primaryKey}

                -- Stop recursion if child ID already exists in the chain
                WHERE INSTR(pt._cte_chain, CONCAT('/', p.{$primaryKey}, '/')) = 0
            )
        SQL;

        return $query
            ->fromRaw("({$cte} SELECT * FROM page_tree WHERE {$this->getKeyName()} != {$parentId}) as {$table}")
            ->select("{$table}.*");
    }

    /**
     * Query all ancestors of a tree node using a single recursive CTE statement.
     *
     * This query does not preload the `path` attribute.
     */
    public function scopeAncestorsOf(Builder $query, int $modelId): Builder
    {
        $table = $this->getTable();
        $primaryKey = $this->getKeyName();
        $parentKey = $this->getParentKeyName();

        $cte = <<<SQL
            WITH RECURSIVE page_tree AS (
                -- Anchor: Starting child page
                SELECT
                    *,
                    CAST(CONCAT('/', {$primaryKey}, '/') AS CHAR(1000)) AS _cte_chain
                FROM {$table}
                WHERE {$primaryKey} = {$modelId}

                UNION ALL

                -- Recursive: Join upward and PREPEND parent slug to preserve the root-down path
                SELECT
                    p.*,
                    CONCAT(pt._cte_chain, p.{$primaryKey}, '/') AS _cte_chain
                FROM {$table} p
                INNER JOIN page_tree pt ON pt.{$parentKey} = p.{$primaryKey}

                -- Cycle safety check
                WHERE INSTR(pt._cte_chain, CONCAT('/', p.{$primaryKey}, '/')) = 0
            )
        SQL;

        return $query
            ->fromRaw("({$cte} SELECT * FROM page_tree WHERE {$primaryKey} != {$modelId}) as {$table}")
            ->select("{$table}.*");
    }

    /**
     * Check if this model instance is a descendant of the ID.
     */
    public function isDescendantOf(int $targetParentId): bool
    {
        $descendantIds = static::descendantsOf($this->getKey())->pluck($this->getKeyName())->all();

        return in_array($targetParentId, $descendantIds, true);
    }

    /**
     * Transform a flat collection into a nested hierarchy tree.
     */
    public static function buildTree(\Illuminate\Support\Collection $models, mixed $parentId = null): \Illuminate\Support\Collection
    {
        /** @var static $instance */
        $instance = new static;
        $parentKey = $instance->getParentKeyName();
        $primaryKey = $instance->getKeyName();

        return $models
            ->where($parentKey, $parentId)
            ->map(function (Model $model) use ($models, $primaryKey) {
                $model->setRelation(
                    'children',
                    static::buildTree($models, $model->getAttribute($primaryKey))
                );

                return $model;
            })
            ->values();
    }

    /**
     * Fetch full structured tree.
     */
    public static function tree(): Collection
    {
        return static::buildTree(static::withTree()->get());
    }

    /**
     * The parent of this node.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, $this->getParentKeyName());
    }

    /**
     * The children of this node.
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, $this->getParentKeyName());
    }

    /**
     * Accessor to computer the `path` attribute.
     */
    public function getPathAttribute()
    {
        // If path was pre-calculated via a Recursive CTE query return it.
        if (isset($this->attributes['path'])) {
            return $this->attributes['path'];
        }

        $primaryId = $this->getKey();
        $parentKey = $this->getParentKeyName();
        $slugKey = $this->getSlugKeyName();
        $slugValue = $this->getAttribute($slugKey);

        // For root nodes we can simply return the slug.
        if (! $this->getAttribute($parentKey) && ! $this->getAttribute($slugKey)) {
            return '/'.$slugValue;
        }

        // Generate the slug from a recursive CTE query for all page ancestors.
        return '/'.static::ancestorsOf($primaryId)->get()
            ->pluck($slugKey)
            ->reverse()
            ->add($slugValue)
            ->join('/');
    }
}
