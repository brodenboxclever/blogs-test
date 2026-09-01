<?php

namespace Modules\Blogs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Blogs\Models\Blog;
use Modules\Blogs\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Blog $blog)
    {
        $filters = $request->validate([
            'q' => 'nullable|string',
            'sort_by' => ['nullable', Rule::in(['title', 'created_at', 'order'])],
            'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => 'nullable|numeric',
        ]);

        $searchTerm = $filters['q'] ?? null;
        $sort = $filters['sort_by'] ?? 'published_at';
        $direction = $filters['sort_direction'] ?? 'desc';

        $posts = Post::query()
            ->withCount('comments')
            ->with('blog')
            ->when($searchTerm, fn ($query, $searchTerm) => $query->whereLike('title', "%{$searchTerm}%"))
            ->where('blog_id', $blog->id)
            ->orderBy($sort, $direction)
            ->orderBy('id', $direction)
            ->paginate(10)
            ->withQueryString()
            ->toResourceCollection();

        return inertia('Blogs/Post/Index', compact('blog', 'posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
