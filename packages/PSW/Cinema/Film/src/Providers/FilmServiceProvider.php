<?php

namespace PSW\Cinema\Film\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use PSW\Cinema\Film\Facades\MasterImage as MasterImageFacade;
use PSW\Cinema\Film\Facades\ReleaseImage as ReleaseImageFacade;
use PSW\Cinema\Film\Models\MasterPage;
use PSW\Cinema\Film\Models\ReleasesPage;
use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Facades\ReleaseImage;
use PSW\Cinema\Film\Observers\MasterObserver;
use PSW\Cinema\Film\Observers\ReleaseObserver;




class FilmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(): void
    { 
        include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'master');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'master');

        $this->publishes([
            dirname(__DIR__) . '/Config/imagecache.php' => config_path('imagecache.php'),
        ]);
        MasterPage::observe(MasterObserver::class);
        ReleasesPage::observe(ReleaseObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();

        // $this->app->singleton('captcha', function ($app) {
        //     return new Captcha();
        // });
        $this->registerConfig();

        $this->registerFacades();
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/product_types.php', 'product_types');

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/system.php', 'core');
    }


        /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades(): void
    {
        /**
         * Master image.
         */
        $loader = AliasLoader::getInstance();

        $loader->alias('masterimage', MasterImageFacade::class);

        $this->app->singleton('masterimage', function () {
            return app()->make(MasterImage::class);
        });

        /**
         * Release image.
         */
        $loader = AliasLoader::getInstance();
        $loader->alias('releaseimage', ReleaseImageFacade::class);

        $this->app->singleton('releaseimage', function () {
            return app()->make(ReleaseImage::class);
        });
    }
}
