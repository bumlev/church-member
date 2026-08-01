<?php

namespace App\Providers;

use App\OpenApi\SwaggerGeneratorFactory;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Schema\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use L5Swagger\GeneratorFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Replace l5-swagger's GeneratorFactory with our custom one
        // so swagger-php uses our PSR-3 logger instead of triggering PHP errors.
        $this->app->bind(GeneratorFactory::class, SwaggerGeneratorFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::defaultStringLength(191);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
