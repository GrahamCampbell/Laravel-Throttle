<?php

/**
 * This file is part of Laravel Throttle by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Throttle;

use Illuminate\Support\ServiceProvider;

/**
 * This is the throttle service provider class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
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
        $this->package('graham-campbell/throttle');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerThrottle();
    }

    /**
     * Register the throttle class.
     *
     * @return void
     */
    protected function registerThrottle()
    {
        $this->app->bindShared('throttle', function ($app) {
            $app = $app['app'];
            $cache = $app['cache'];
            $throttler = $app['config']['throttle::throttler'];

            return new Classes\Throttle($app, $cache, $throttler);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'throttle'
        );
    }
}
