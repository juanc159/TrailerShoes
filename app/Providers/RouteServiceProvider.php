<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $routesApi = [
            base_path('routes/api.php'),
            base_path('routes/Api/authentication.php'),
        ];
        $routesAuthApi = [
            base_path('routes/Api/permission.php'),
            base_path('routes/Api/role.php'),
            base_path('routes/Api/menu.php'),
            base_path('routes/Api/user.php'),
            base_path('routes/Api/charge.php'),
            base_path('routes/Api/company.php'),
        ];

        $this->routes(function () use ($routesAuthApi, $routesApi) {
            Route::middleware('auth:api')
                ->prefix('api')
                ->group($routesAuthApi);

            Route::middleware('api')
                ->prefix('api')
                ->group($routesApi);

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
