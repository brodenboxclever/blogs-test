<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Blogs\Models\Comment;
use Modules\Blogs\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_post_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->foreignIdFor(Comment::class, 'parent_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Post::class)->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('body', 510);

            $table->boolean('is_spam')->default(false);
            $table->boolean('is_approved')->default(false);

            $table->clientSession();

            $table->timestamps();

            // Foreign key references a unique index on the comments table.
            // This constraint ensures that a comment reply belongs to the same blog post as the parent comment.
            $table->unique(['id', 'post_id']);
            $table->foreign(['parent_id', 'post_id'])
                ->references(['id', 'post_id'])
                ->on($table->getTable())
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_comments');
    }
};
