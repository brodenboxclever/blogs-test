<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'title' => $this->_generate_default_title(),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * Create a fallback title for the inertia payload based on the module, controller and method names.
     *
     * For example:
     * `Modules\Pages\Http\Controllers\PageController::index` => *Page listings*
     * `Modules\Blogs\Http\Controllers\BlogController::create` => *Create Blog*
     * `Modules\Blogs\Http\Controllers\BlogPostController::edit` => *Manage Blog Post*
     * `Modules\Foo\Http\Controllers\BarController::baz` => *Foo Bar Baz*
     */
    private function _generate_default_title(): string
    {
        $controllerName = request()->route()?->getControllerClass();
        $methodName = request()->route()?->getActionMethod();
        preg_match('/^Modules\\\\([^\\\\]+)\\\\(.+)$/', $controllerName, $matches);
        $moduleName = $matches[1] ?? false;

        // i.e. "Blog"
        $moduleTitle = Str::of($moduleName)
            ->singular()
            ->headline()
            ->toString();

        // i.e. "Blog Post"
        $modelTitle = Str::of(class_basename($controllerName))
            ->after($moduleTitle)
            ->beforeLast('Controller')
            ->singular()
            ->headline()
            ->toString();

        if ($modelTitle !== $moduleTitle) {
            $moduleTitle .= ' '.$modelTitle;
        }

        $defaultTitle = '';
        if ($moduleName) {
            $defaultTitle = match ($methodName) {
                'create' => 'Create '.$moduleTitle,
                'edit' => 'Manage '.$moduleTitle,
                'index' => $moduleTitle.' Listings',
                default => Str::of($moduleName.' '.$methodName)->headline()->trim(),
            };
        }

        return $defaultTitle;
    }
}
