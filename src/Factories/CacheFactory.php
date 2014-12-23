<?php

/*
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

namespace GrahamCampbell\Throttle\Factories;

use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Throttlers\CacheThrottler;
use Illuminate\Cache\Repository;

/**
 * This is the cache throttler factory class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class CacheFactory implements FactoryInterface
{
    /**
     * The cache instance.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Cache\Repository $cache
     *
     * @return void
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Make a new cache throttler instance.
     *
     * @param \GrahamCampbell\Throttle\Data $data
     *
     * @return \GrahamCampbell\Throttle\Throttlers\CacheThrottler
     */
    public function make(Data $data)
    {
        $store = $this->cache->tags('throttle', $data->getIp());

        return new CacheThrottler($store, $data->getRouteKey(), $data->getLimit(), $data->getTime());
    }

    /**
     * Get the cache instance.
     *
     * @return \Illuminate\Cache\Repository
     */
    public function getCache()
    {
        return $this->cache;
    }
}
