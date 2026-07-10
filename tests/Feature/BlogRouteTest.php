<?php

use App\Models\User;

test('authenticated users can access the blog index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('blogs.index'))
        ->assertOk();
});
