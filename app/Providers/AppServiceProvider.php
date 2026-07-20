<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
        $this->configureDefaults();
        $this->configureBlueprintMacros();
        $this->configureFactoryNameResolvers();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Configures Eloquent Models to:
     *
     * - Throw an Exception when accessing missing attributes
     * - Throw an Exception when filling missing attributes
     * - Detect queries within loops and generate a single query
     */
    protected function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
    }

    protected function configureBlueprintMacros(): void
    {
        Blueprint::macro('openGraphs', function () {
            $this->string('og_title')->nullable();
            $this->string('og_description')->nullable();
            $this->string('og_image')->nullable();
            $this->string('og_image_alt')->nullable();
        });

        Blueprint::macro('slug', function (string $column = 'slug') {
            return $this->string('slug')->unique();
        });

        Blueprint::macro('image', function (string $column = 'image') {
            $image = $this->string($column);
            $this->string($column.'_alt')->nullable();

            return $image;
        });

        Blueprint::macro('readonly', function () {
            $this->boolean('is_readonly')->default(false)->comment('Whether the record is prevented from any further updates.');
            $this->foreignIdFor(User::class, 'readonly_by')->nullable()->comment('The user who marked the record as readonly.');
            $this->string('readonly_at')->nullable()->comment('The datetime when the record was marked as readonly.');
            $this->string('readonly_reason')->nullable();
        });

        Blueprint::macro('order', function ($column = 'order') {
            return $this->tinyInteger($column, unsigned: true)->nullable();
        });
    }

    /**
     * Extend the factory name resolvers to account for module namespaces.
     *
     * For example:
     * - `App\Models\Foo` ⇄ `Database\Factories\FooFactory`
     * - `Modules\Foo\Models\Bar` ⇄ `Modules\Foo\Database\Factories\BarFactory`
     */
    protected function configureFactoryNameResolvers(): void
    {
        Factory::guessModelNamesUsing(function ($factory) {
            preg_match('/^Modules\\\\([^\\\\]+)\\\\Database\\\\Factories\\\\(.+)$/', $factory::class, $matches);
            $moduleName = $matches[1] ?? false;
            $modelBasename = Str::replaceLast('Factory', '', $matches[2] ?? class_basename($factory::class));

            if ($moduleName) {
                return 'Modules\\'.$moduleName.'\\Models\\'.$modelBasename;
            }

            return 'App\\Models\\'.$modelBasename;
        });

        Factory::guessFactoryNamesUsing(function ($modelName) {
            preg_match('/^Modules\\\\([^\\\\]+)\\\\Models\\\\(.+)$/', $modelName, $matches);
            $moduleName = $matches[1] ?? false;
            $modelBasename = $matches[2] ?? class_basename($modelName);

            if ($moduleName) {
                return 'Modules\\'.$moduleName.'\\Database\\Factories\\'.$modelBasename.'Factory';
            }

            return 'Database\\Factories\\'.$modelBasename.'Factory';
        });
    }
}
