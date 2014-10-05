<?php

/**
 * This file is part of Laravel Throttle by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Throttle;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the throttle service provider class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
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

        $this->setupFilters();
    }

    /**
     * Setup the filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $router = $this->app['router'];
        $throttle = $this->app['throttle'];

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
        $this->app->bindShared('throttle.transformer', function ($app) {
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
        return array(
            'throttle',
            'throttle.factory',
            'throttle.transformer',
        );
    }
}
