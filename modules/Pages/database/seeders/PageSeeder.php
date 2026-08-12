<?php

namespace Modules\Pages\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Pages\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $first = Page::factory(500)->create();
        $second = Page::factory(500)->recycle($first)->child()->create();
        $third = Page::factory(500)->recycle($second)->child()->create();
        $forth = Page::factory(500)->recycle($third)->child()->create();
        $fifth = Page::factory(500)->recycle($forth)->child()->create();

        Page::factory(1)->recycle(User::all())->readonly()->create();
    }
}
