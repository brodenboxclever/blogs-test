<?php

namespace Modules\Blogs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blogs\Models\Blog;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::factory(20)->create();
    }
}
