<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
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
