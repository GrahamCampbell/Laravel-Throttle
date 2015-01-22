<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Throttlers;

use Countable;
use Illuminate\Contracts\Cache\Store;

/**
 * This is the cache throttler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CacheThrottler implements ThrottlerInterface, Countable
{
    /**
     * The store instance.
     *
     * @var \Illuminate\Contracts\Cache\Store
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
     * @param \Illuminate\Contracts\Cache\Store $store
     * @param string                            $key
     * @param int                               $limit
     * @param int                               $time
     *
     * @return void
     */
    public function __construct(Store $store, $key, $limit, $time)
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
     * @return \Illuminate\Contracts\Cache\Store
     */
    public function getStore()
    {
        return $this->store;
    }
}
