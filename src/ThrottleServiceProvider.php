<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle;

use Illuminate\Support\ServiceProvider;

/**
 * This is the throttle service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ThrottleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('graham-campbell/throttle', 'graham-campbell/throttle', __DIR__);

        include __DIR__.'/filters.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();
        $this->registerTransformer();
        $this->registerThrottle();
    }

    /**
     * Register the factory class.
     *
     * @return void
     */
    protected function registerFactory()
    {
        $this->app->bindShared('throttle.factory', function ($app) {
            $cache = $app['cache']->driver($app['config']['graham-campbell/throttle::driver']);

            return new Factories\CacheFactory($cache);
        });

        $this->app->alias('throttle.factory', 'GrahamCampbell\Throttle\Factories\FactoryInterface');
    }

    /**
     * Register the transformer class.
     *
     * @return void
     */
    protected function registerTransformer()
    {
        $this->app->bindShared('throttle.transformer', function () {
            return new Transformers\TransformerFactory();
        });

        $this->app->alias('throttle.transformer', 'GrahamCampbell\Throttle\Transformers\TransformerFactory');
    }

    /**
     * Register the throttle class.
     *
     * @return void
     */
    protected function registerThrottle()
    {
        $this->app->bindShared('throttle', function ($app) {
            $factory = $app['throttle.factory'];
            $transformer = $app['throttle.transformer'];

            return new Throttle($factory, $transformer);
        });

        $this->app->alias('throttle', 'GrahamCampbell\Throttle\Throttle');
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
