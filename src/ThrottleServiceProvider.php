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

use Illuminate\Routing\Router;
use Orchestra\Support\Providers\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the throttle service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
        $this->addConfigComponent('graham-campbell/throttle', 'graham-campbell/throttle', realpath(__DIR__.'/../config'));

        $this->setupFilters($this->app['router'], $this->app['throttle']);
    }

    /**
     * Setup the filters.
     *
     * @param \Illuminate\Routing\Router        $router
     * @param \GrahamCampbell\Throttle\Throttle $throttle
     *
     * @return void
     */
    protected function setupFilters(Router $router, Throttle $throttle)
    {
        $router->filter('throttle', function ($route, $request, $limit = 10, $time = 60) use ($throttle) {
            if (!$throttle->attempt($request, $limit, $time)) {
                throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
            }
        });
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
        $this->app->singleton('throttle.factory', function ($app) {
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
        $this->app->singleton('throttle.transformer', function () {
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
        $this->app->singleton('throttle', function ($app) {
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
