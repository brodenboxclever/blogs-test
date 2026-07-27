<?php

namespace Modules\Blogs\Database\Factories;

use App\Models\User;
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
            'image' => fake()->optional()->imageUrl(800, 600, 'abstract'),
            'image_alt' => fake()->optional()->sentence(3),
            'order' => fake()->optional()->numberBetween(1, 100),
        ];
    }

    public function readonly()
    {
        return $this->state(fn (array $attributes) => [
            'is_readonly' => true,
            'readonly_by' => User::inRandomOrder()->value('id'),
            'readonly_at' => fake()->datetime(),
            'readonly_reason' => fake()->optional(80)->sentence(1),
        ]);
    }
}
