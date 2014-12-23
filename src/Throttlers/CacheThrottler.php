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

namespace GrahamCampbell\Throttle\Throttlers;

use Countable;
use Illuminate\Cache\StoreInterface;

/**
 * This is the cache throttler class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class CacheThrottler implements ThrottlerInterface, Countable
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
     * The expiration time.
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
     * @param \Illuminate\Cache\StoreInterface $store
     * @param string                           $key
     * @param int                              $limit
     * @param int                              $time
     *
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
     * Hit the throttle.
     *
     * @return $this
     */
    public function hit()
    {
        $this->number = $this->count() + 1;

        $this->store->put($this->key, $this->number, $this->time);

        return $this;
    }

    /**
     * Clear the throttle.
     *
     * @return $this
     */
    public function clear()
    {
        $this->number = 0;

        $this->store->put($this->key, $this->number, $this->time);

        return $this;
    }

    /**
     * Get the throttle hit count.
     *
     * @return int
     */
    public function count()
    {
        if ($this->number !== null) {
            return $this->number;
        }

        $this->number = (int) $this->store->get($this->key);

        if (!$this->number) {
            $this->number = 0;
        }

        return $this->number;
    }

    /**
     * Check the throttle.
     *
     * @return bool
     */
    public function check()
    {
        return ($this->count() <= $this->limit + 1);
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
