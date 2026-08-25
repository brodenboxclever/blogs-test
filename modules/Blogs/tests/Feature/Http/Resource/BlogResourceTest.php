<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blogs\Models\Blog;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('blog resource hides sensitive fields from guests', function () {
    $blog = Blog::factory()->create()->toResource()->resolve();
    expect($blog)->not->toHaveKeys(['created_at', 'updated_at', 'deleted_at', 'is_readonly']);

    $this->createUserAndLogin();

    $blog = Blog::factory()->create()->toResource()->resolve();
    expect($blog)->toHaveKeys(['created_at', 'updated_at', 'deleted_at', 'is_readonly']);
});
