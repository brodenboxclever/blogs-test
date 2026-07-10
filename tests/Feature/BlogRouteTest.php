<?php

use App\Models\User;

test('authenticated users can access the blog index', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('blogs.index'))
        ->assertOk();
});
