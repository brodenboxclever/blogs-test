<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Modules\Blogs\Models\Blog;
use Modules\Blogs\Models\Post;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('redirects to login page', function () {
    $this->get(route('blogs.blog.index'))
        ->assertRedirectToRoute('login');
});

it('returns correct component', function () {
    $this->createUserAndLogin();

    $blog = Blog::factory()->create();
    Post::factory(5)->recycle($blog)->create();

    $this->get(route('blogs.blog.post.index', ['blog' => $blog]))
        ->assertInertia(
            fn (AssertableInertia $inertia) => $inertia
                ->component('Blogs/Post/Index', true)
                ->has('posts')
        );
});
