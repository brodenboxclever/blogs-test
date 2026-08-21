<?php

namespace Modules\Blogs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blogs\Models\Blog;
use Modules\Blogs\Models\Comment;
use Modules\Blogs\Models\Post;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = Blog::factory(5)->create();
        Post::factory(20)->recycle($blogs)->create();
        Post::factory(3)->recycle($blogs)->future()->create();
        Post::factory(2)->recycle($blogs)->past()->create();

        $comments = Comment::factory(25)->recycle(Post::all())->create();
        $replies = Comment::factory(10)->reply()->recycle($comments)->create();

        Comment::factory(10)->reply()->recycle($replies)->create();
        Comment::factory(5)->unapproved()->recycle(Post::all())->create();
    }
}
