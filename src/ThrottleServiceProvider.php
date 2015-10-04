<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle;

use GrahamCampbell\Throttle\Factories\CacheFactory;
use GrahamCampbell\Throttle\Factories\FactoryInterface;
use GrahamCampbell\Throttle\Transformers\TransformerFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * This is the throttle service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ThrottleServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig($this->app);
    }

    /**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function setupConfig(Application $app)
    {
        $source = realpath(__DIR__.'/../config/throttle.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('throttle.php')]);
        } elseif (class_exists('Laravel\Lumen\Application', false)) {
            $app->configure('throttle');
        }

        $this->mergeConfigFrom($source, 'throttle');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory($this->app);
        $this->registerTransformer($this->app);
        $this->registerThrottle($this->app);
    }

    /**
     * Register the factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerFactory(Application $app)
    {
        $app->singleton('throttle.factory', function (Application $app) {
            $cache = $app->cache->driver($app->config->get('throttle.driver'));

            return new CacheFactory($cache);
        });

        $app->alias('throttle.factory', CacheFactory::class);
        $app->alias('throttle.factory', FactoryInterface::class);
    }

    /**
     * Register the transformer class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerTransformer(Application $app)
    {
        $app->singleton('throttle.transformer', function () {
            return new TransformerFactory();
        });

        $app->alias('throttle.transformer', TransformerFactory::class);
    }

    /**
     * Register the throttle class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerThrottle(Application $app)
    {
        $app->singleton('throttle', function ($app) {
            $factory = $app['throttle.factory'];
            $transformer = $app['throttle.transformer'];

            return new Throttle($factory, $transformer);
        });

        $app->alias('throttle', Throttle::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'throttle',
            'throttle.factory',
            'throttle.transformer',
        ];
    }
}
