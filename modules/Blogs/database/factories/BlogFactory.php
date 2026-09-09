<?php

namespace Modules\Blogs\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Blogs\Models\Blog;

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
            'title' => fake()->sentence(4),
            'order' => fake()->optional()->numberBetween(1, 100),
            'is_enabled' => fake()->boolean(80),
        ];
    }

    public function readonly()
    {
        return $this->state(fn (array $attributes) => [
            'is_readonly' => true,
            'readonly_by' => User::factory(),
            'readonly_at' => fake()->datetime(),
            'readonly_reason' => fake()->optional(80)->sentence(1),
        ]);
    }
}
