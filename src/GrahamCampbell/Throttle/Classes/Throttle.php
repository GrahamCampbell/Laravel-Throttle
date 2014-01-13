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

use Illuminate\Http\Request;
use Illuminate\Cache\CacheManager;

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
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @param  string  $throttler
     * @return void
     */
    public function __construct(CacheManager $cache, $throttler)
    {
        $this->cache = $cache;
        $this->throttler = $throttler;
    }

    /**
     * Get the throttler.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $limit
     * @param  int  $time
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function get(Request $request, $limit = 10, $time = 60)
    {
        $store = $this->getStore($request);
        $key = $this->getKey($request);

        $throttler = $this->throttler;

        return new $throttler($store, $key, $limit, $time);
    }

    /**
     * Get the store.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Cache\StoreInterface
     */
    protected function getStore(Request $request)
    {
        return $this->cache->tags('throttle', $request->getClientIp());
    }

    /**
     * Get the key.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getKey(Request $request)
    {
        return md5($route->path());
    }

    /**
     * Get the cache instance.
     *
     * @return \Illuminate\Cache\CacheManager
     */
    public function getCache()
    {
        return $this->cache;
    }
}
