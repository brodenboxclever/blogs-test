<?php

namespace Modules\Pages\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Pages\Models\Page;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ucwords(fake()->words(3, true));
        $image = fake()->optional()->imageUrl(800, 600, 'abstract');

        return [
            'uuid' => fake()->unique()->uuid(),
            'title' => $title,
            'slug' => fake()->boolean(90) ? Str::slug($title) : fake()->slug(2),
            'image' => $image,
            'image_alt' => $image ? fake()->optional()->sentence(3) : null,
            'og_title' => fake()->optional()->sentence(3),
            'og_description' => fake()->optional()->sentence(8),
            'og_image' => fake()->optional()->imageUrl(1200, 630, 'business'),
            'og_image_alt' => fake()->optional()->sentence(3),
            'is_analytics_allowed' => fake()->boolean(80),
            'is_visible_in_nav' => fake()->boolean(80),
            'is_enabled' => fake()->boolean(80),
            'is_indexable' => fake()->boolean(80),
            'order' => fake()->optional()->numberBetween(1, 100),
        ];
    }

    public function child()
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Page::inRandomOrder()->value('id'),
        ]);
    }

    public function childOf(Page $page)
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $page->id,
        ]);
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
