<?php

namespace Sideapps\NovaTranslation;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sideapps\NovaTranslation\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-translation');

        $this->registerTranslations();

        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishes([
            __DIR__.'/../config/nova-translation.php' => config_path('nova-translation.php'),
        ], 'nova-translation');

        $this->publishes([
            __DIR__.'/../resources/lang/' => resource_path('lang/vendor/nova-translation'),
        ]);

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
                ->prefix('nova-vendor/nova-translation')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-translation.php', 'nova-translation');
    }

    protected function registerTranslations()
    {
        $currentLocale = app()->getLocale();

        Nova::translations(__DIR__.'/../resources/lang/'.$currentLocale.'.json');
        Nova::translations(resource_path('lang/vendor/nova-translation/'.$currentLocale.'.json'));

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova-translation');
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadJSONTranslationsFrom(resource_path('lang/vendor/nova-translation'));
    }
}
