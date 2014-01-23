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

namespace GrahamCampbell\Throttle\Throttlers;

use Illuminate\Cache\StoreInterface;

/**
 * This is the cache throttler class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
class CacheThrottler implements ThrottlerInterface
{
    /**
     * The store instance.
     *
     * @var \Illuminate\Cache\StoreInterface
     */
    protected $store;

    /**
     * The key.
     *
     * @var string
     */
    protected $key;

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
     * @param  \Illuminate\Cache\StoreInterface  $store
     * @param  string  $key
     * @param  int     $limit
     * @param  int     $time
     * @return void
     */
    public function __construct(StoreInterface $store, $key, $limit, $time)
    {
        $this->store = $store;
        $this->key = $key;
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
        return $this->hit()->check();
    }

    /**
     * Hit the the throttle.
     *
     * @return $this
     */
    public function hit()
    {
        $count = $this->count() + 1;

        $this->store->put($this->key, $count, $this->time);

        $this->number = $count;

        return $this;
    }

    /**
     * Clear the the throttle.
     *
     * @return $this
     */
    public function clear()
    {
        $this->store->put($this->key, 0, $this->time);

        $this->number = 0;

        return $this;
    }

    /**
     * Get the throttle hit count.
     *
     * @return int
     */
    public function count()
    {
        if (!is_null($this->number)) {
            return $this->number;
        }

        $count = $this->store->get($this->key);

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

    /**
     * Get the store instance.
     *
     * @return \Illuminate\Cache\StoreInterface
     */
    public function getStore()
    {
        return $this->store;
    }
}
