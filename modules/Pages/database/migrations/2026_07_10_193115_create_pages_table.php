<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Pages\Models\Page;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(Page::class, 'parent_id')->nullable()->constrained()->restrictOnDelete();

            $table->string('title');
            $table->slug();

            $table->image()->nullable();

            $table->openGraphs();

            $table->boolean('is_analytics_allowed')->default(true);
            $table->boolean('is_visible_in_nav')->default(true);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_indexable')->default(true);

            $table->readonly();

            $table->order();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
