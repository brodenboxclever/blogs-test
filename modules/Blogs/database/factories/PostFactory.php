<?php

namespace Modules\Blogs\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Blogs\Models\Blog;
use Modules\Blogs\Models\Post;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ucwords(fake()->words(rand(5, 10), true));
        $image = fake()->optional()->imageUrl(800, 600, 'abstract');

        return [
            'uuid' => fake()->unique()->uuid(),

            'blog_id' => Blog::factory(),
            'user_id' => User::factory(),

            'title' => $title,
            'body' => fake()->html(),
            'slug' => fake()->boolean(90) ? Str::slug($title) : fake()->slug(2),
            'image' => $image,
            'image_alt' => $image ? fake()->optional()->sentence(3) : null,

            'og_title' => fake()->optional()->sentence(3),
            'og_description' => fake()->optional()->sentence(8),
            'og_image' => fake()->optional()->imageUrl(1200, 630, 'business'),
            'og_image_alt' => fake()->optional()->sentence(3),

            'meta_title' => fake()->optional()->sentence(3),
            'meta_description' => fake()->optional()->sentence(3),

            'is_enabled' => fake()->boolean(80),
            'is_indexable' => fake()->boolean(80),
            'is_visible_in_blog' => fake()->boolean(80),
            'is_comments_enabled' => fake()->boolean(80),
        ];
    }

    public function future()
    {
        return $this->state(fn (array $attributes) => [

            'published_at' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'unpublished_at' => fake()->optional(0.2)->dateTimeBetween('+2 week', '+1 year'),
        ]);
    }

    public function past()
    {
        return $this->state(fn (array $attributes) => [

            'unpublished_at' => fake()->dateTimeBetween('-4 weeks', '-1 week'),
        ]);
    }
}
