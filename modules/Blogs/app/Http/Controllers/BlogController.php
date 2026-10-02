<?php

namespace Modules\Blogs\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Blogs\Http\Requests\BlogRequest;
use Modules\Blogs\Http\Requests\BulkUpdateBlogRequest;
use Modules\Blogs\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => 'nullable|string',
            'sort_by' => ['nullable', Rule::in(['title', 'posts_count', 'created_at', 'order'])],
            'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => 'nullable|numeric',
        ]);

        $searchTerm = $filters['q'] ?? null;
        $sort = $filters['sort_by'] ?? 'order';
        $direction = $filters['sort_direction'] ?? 'desc';

        $blogs = Blog::query()
            ->withCount('posts')
            ->when($searchTerm, fn ($query, $searchTerm) => $query->whereLike('title', "%{$searchTerm}%"))
            ->orderBy($sort, $direction)
            ->orderBy('id', $direction)
            ->paginate(10)
            ->withQueryString()
            ->toResourceCollection();

        return inertia('Blogs/Index', [
            'blogs' => $blogs,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Blogs/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        Blog::create($request->safe()->all());

        return to_route('blogs::blogs.index')->with('success', 'Blog successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return inertia('Blogs/Show', ['blog' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return inertia('Blogs/Edit', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        dd('test');
        $blog->update($request->validated());

        return to_route('blogs::blogs.index')->with('success', 'Blog successfully updated.');
    }

    /**
     * Update multiple resources in storage.
     */
    public function bulkUpdate(BulkUpdateBlogRequest $request)
    {
        $model = (new Blog);
        $keyName = $model->getRouteKeyName();
        $tableName = $model->getTable();

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $keyName) {
                foreach ($validated['blogs'] as $item) {

                    // Extract update attributes excluding the 'id' key
                    $attributes = collect($item)->except($keyName)->filter(fn ($value) => $value !== null)->toArray();

                    if (! empty($attributes)) {
                        $blog = Blog::find($item[$keyName]);
                    }
                    if (! empty($blog)) {
                        $blog->update($attributes);
                    } else {
                        throw new Exception("Blog ID {$item[$keyName]} does not exist.");
                    }
                }
            });
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                $tableName => ['Failed to perform bulk update: '.$e->getMessage()],
            ]);
        }

        return to_route('blogs::blogs.index')->with('success', 'Blogs successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete($blog);

        return to_route('blogs::blogs.index')->with('success', 'Blog successfully trashed.');
    }
}
