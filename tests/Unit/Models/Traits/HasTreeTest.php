<?php

namespace Tests\Unit\Models\Traits;

use App\Traits\Models\HasTree; // Adjust to your trait's actual namespace
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// 1. Define a concrete Fake Model inside the test file (or beforeEach)
class FakeNode extends Model
{
    use HasTree;

    protected $table = 'fake_nodes';

    protected $fillable = ['parent_id', 'slug'];
}

// 2. Set up the temporary table before running the tests
beforeEach(function () {
    Schema::create('fake_nodes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('parent_id')->nullable()->constrained('fake_nodes')->nullOnDelete();
        $table->string('slug');
        $table->timestamps();
    });
});

// 3. Tests operating strictly on FakeNode
it('exposes tree data', function (): void {
    $node = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'test']);

    expect($node->getParentKeyName())->toBe('parent_id');
    expect($node->getSlugKeyName())->toBe('slug');
});

it('generates paths', function (): void {
    $n1 = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'foo']);
    $n2 = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $n1->id, 'slug' => 'bar']);
    $n3 = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $n2->id, 'slug' => 'baz']);

    expect($n1->path)->toBe('/foo');
    expect($n2->path)->toBe('/foo/bar');
    expect($n3->path)->toBe('/foo/bar/baz');
});

it('prevents a page from being its own parent', function () {
    $node = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'foo']);

    $this->expectException(ValidationException::class);
    $node->update(['parent_id' => $node->id]);
})->throws(ValidationException::class);

it('prevents descendant as a parent', function () {
    $n1 = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'foo']);
    $n2 = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'bar']);
    $n3 = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'baz']);

    $n3->update(['parent_id' => $n2->id]);
    $n2->update(['parent_id' => $n1->id]);

    $this->expectException(ValidationException::class);
    $n1->update(['parent_id' => $n3->id]);
});

it('prevents duplicate paths relationships', function () {
    $nA = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'a']);
    $nB = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $nA->id, 'slug' => 'b']);
    $nC = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $nA->id, 'slug' => 'c']);

    $this->expectException(ValidationException::class);
    $nC->update(['slug' => 'b']);
});

it('prevents duplicate paths relationships after changing parent', function () {
    $nA = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'a']);
    $nB = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $nA->id, 'slug' => 'b']);
    $nC = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $nA->id, 'slug' => 'a']);

    $this->expectException(ValidationException::class);
    $nC->update(['parent_id' => null]);
});

it('path prevents n plus one problem', function () {
    $n1 = Tests\Unit\Models\Traits\FakeNode::create(['slug' => 'foo']);
    $n2 = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $n1->id, 'slug' => 'bar']);
    $n3 = Tests\Unit\Models\Traits\FakeNode::create(['parent_id' => $n2->id, 'slug' => 'baz']);

    DB::enableQueryLog();
    expect(Tests\Unit\Models\Traits\FakeNode::find($n3->id)->path)->toBe('/foo/bar/baz');
    expect(DB::getQueryLog())->toHaveCount(2);
});
