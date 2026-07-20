<?php

namespace Modules\Pages\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Pages\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::factory(5)->create();
        Page::factory(5)->child()->create();
        Page::factory(5)->child()->create();
        Page::factory(5)->child()->create();
        Page::factory(5)->child()->create();
    }
}
