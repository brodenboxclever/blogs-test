<?php

namespace Modules\Blogs\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blogs\Models\Comment;
use Modules\Blogs\Models\Post;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->unique()->uuid(),
            'post_id' => Post::factory(),

            'name' => fake()->name(),
            'email' => fake()->email(),
            'body' => fake()->realText(rand(255, 510)),

            'is_spam' => fake()->boolean(20),
            'is_approved' => true,

            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'referrer' => fake()->url(),
            'phpsessid' => bin2hex(random_bytes(16)),
        ];
    }

    /**
     * Create a reply to another comment of the same blog post.
     */
    public function reply()
    {
        return $this->state(function (array $attributes) {
            $parent = $this->getRandomRecycledModel(Comment::class)
                ?? Comment::factory()->recycle($this->recycle)->create();

            return [
                'parent_id' => $parent->id,
                'post_id' => $parent->post_id,
            ];
        });
    }

    public function unapproved()
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }
}
