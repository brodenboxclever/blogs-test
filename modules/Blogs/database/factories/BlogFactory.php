<?php

namespace Modules\Blogs\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Blogs\Models\Blog;
use Modules\Pages\Models\Page;

/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'page_id' => fake()->optional()->boolean() ? Page::factory() : null,
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(),
            'image' => fake()->optional()->imageUrl(800, 600, 'abstract'),
            'image_alt' => fake()->optional()->sentence(3),
            'is_readonly' => false,
            'readonly_by' => null,
            'readonly_at' => null,
            'readonly_reason' => null,
            'order' => fake()->optional()->numberBetween(1, 100),
        ];
    }
}
