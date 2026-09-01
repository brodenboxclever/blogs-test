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
        $blogs = Blog::factory(50)->create();
        Post::factory(1000)->recycle($blogs)->create();
        Post::factory(300)->recycle($blogs)->future()->create();
        Post::factory(200)->recycle($blogs)->past()->create();

        $comments = Comment::factory(2500)->recycle(Post::all())->create();
        $replies = Comment::factory(1000)->reply()->recycle($comments)->create();

        Comment::factory(1000)->reply()->recycle($replies)->create();
        Comment::factory(500)->unapproved()->recycle(Post::all())->create();
    }
}
