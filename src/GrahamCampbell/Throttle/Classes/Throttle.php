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

namespace GrahamCampbell\Throttle\Classes;

use Illuminate\Cache\CacheManager;
use Illuminate\Foundation\Application;

/**
 * This is the throttle class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
class Throttle
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The cache instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * The throttler.
     *
     * @var string
     */
    protected $throttler;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return void
     */
    public function __construct(Application $app, CacheManager $cache, $throttler)
    {
        $this->app = $app;
        $this->cache = $cache;
    }

    public function get($route, $request, $limit = 10, $time = 60)
    {
        $app = $this->app;
        $cache = $this->cache->tags('throttle', $route);
        $ip = $request->getClientIp();

        $throttler = $this->throttler;

        return new $throttler($app, $cache, $route, $ip, $limit, $time);
    }
}
