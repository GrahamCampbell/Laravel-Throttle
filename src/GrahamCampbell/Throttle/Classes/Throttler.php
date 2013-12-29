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

use Illuminate\Cache\TaggedCache;
use Illuminate\Foundation\Application;

/**
 * This is the throttler class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
class Throttler
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The tagged cache instance.
     *
     * @var \Illuminate\Cache\TaggedCache
     */
    protected $cache;

    /**
     * The route.
     *
     * @var string
     */
    protected $route;

    /**
     * The client ip.
     *
     * @var string
     */
    protected $ip;

    /**
     * The request limit.
     *
     * @var int
     */
    protected $limit;

    /**
     * The the expiration time.
     *
     * @var int
     */
    protected $time;

    /**
     * The number of requests.
     *
     * @var int
     */
    protected $number;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Illuminate\Cache\TaggedCache  $cache
     * @return void
     */
    public function __construct(Application $app, TaggedCache $cache, $route, $ip, $limit, $time)
    {
        $this->app = $app;
        $this->cache = $cache;
        $this->route = $route;
        $this->ip = $ip;
        $this->limit = $limit;
        $this->time = $time;
    }

    /**
     * Rate limit access to a resource.
     *
     * @return bool
     */
    public function attempt()
    {
        $this->hit();

        return $this->check();
    }

    /**
     * Hit the the throttle.
     *
     * @return void
     */
    public function hit()
    {
        $this->cache->add($this->ip, 0, $this->time);

        $this->number = $this->cache->increment($this->ip);
    }

    /**
     * Get the throttle hit count.
     *
     * @return int
     */
    public function count()
    {
        if ($this->number) {
            return $this->number;
        }

        $count = $this->cache->get($this->ip);

        if ($count) {
            return $count;
        }

        return 0;
    }

    /**
     * Check the throttle.
     *
     * @return bool
     */
    public function check()
    {
        if ($this->count() > $this->limit) {
            return false;
        }

        return true;
    }
}
