<?php

namespace OceanDBA\Graphitti;

use GuzzleHttp\Handler\CurlHandler;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OceanDBA\Graphitti\Renderer\Renderable;

class GraphittiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();

        $this->app->resolving(Renderable::class, function (Renderable $renderable, Application $app) {
            $renderable->host($app['config']['graphitti']['default'])
                       ->handler(new CurlHandler());
        });
    }

    /**
     * Setup the configuration for Graphitti.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/graphitti.php', 'graphitti'
        );
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/graphitti.php' => $this->app->configPath('graphitti.php'),
            ], 'graphitti-config');
        }
    }
}
