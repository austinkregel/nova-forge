<?php

namespace Kregel\NovaForge;

use Kregel\NovaForge\Contracts\Repositories\ServerRepositoryContract;
use Kregel\NovaForge\Contracts\Repositories\SiteRepositoryContract;
use Kregel\NovaForge\Repositories\ServerRepository;
use Kregel\NovaForge\Repositories\SiteRepository;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kregel\NovaForge\Http\Middleware\Authorize;

class ForgeToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-forge');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->namespace('Kregel\\NovaForge\\Http\\Controllers')
            ->prefix('nova-vendor/nova-forge')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Contracts\NovaForgeDataStoreContract::class, NovaForgeDataStore::class);
        $this->app->bind(ServerRepositoryContract::class, ServerRepository::class);
        $this->app->bind(SiteRepositoryContract::class, SiteRepository::class);
    }
}
