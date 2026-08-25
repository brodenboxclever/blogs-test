<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('redirects to login page', function () {
    $this->get(route('blogs.blog.index'))
        ->assertRedirectToRoute('login');
});
