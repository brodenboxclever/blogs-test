<?php

use App\Models\User;
use App\Providers\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Modules\Blogs\Models\Blog;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Blog::class)->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->slug();
            $table->text('body');
            $table->image()->nullable();

            $table->openGraphs();

            $table->meta();

            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_indexable')->default(true);
            $table->boolean('is_visible_in_blog')->default(true);
            $table->boolean('is_comments_enabled')->default(true);

            $table->dateTime('published_at')->nullable();
            $table->dateTime('unpublished_at')->nullable();

            $table->softDeletes();

            $table->timestamps();

            $table->unique(['blog_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
