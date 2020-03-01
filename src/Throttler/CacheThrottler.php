<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Throttler;

use Countable;
use Illuminate\Cache\RedisStore;
use Illuminate\Contracts\Cache\Store;

/**
 * This is the cache throttler class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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
     * The expiration time in seconds.
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
    public function __construct(Store $store, string $key, int $limit, int $time)
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
        $response = $this->check();

        $this->hit();

        return $response;
    }

    /**
     * Hit the throttle.
     *
     * @return $this
     */
    public function hit()
    {
        if ($this->store instanceof RedisStore) {
            return $this->hitRedis();
        }

        if ($this->count()) {
            $this->store->increment($this->key);
            $this->number++;
        } else {
            $this->store->put($this->key, 1, $this->time);
            $this->number = 1;
        }

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
        return $this->count() < $this->limit;
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

    /**
     * An atomic hit implementation for redis.
     *
     * @return $this
     */
    protected function hitRedis()
    {
        $lua = 'local v = redis.call(\'incr\', KEYS[1]) '.
               'if v>1 then return v '.
               'else redis.call(\'setex\', KEYS[1], ARGV[1], 1) return 1 end';

        $this->number = $this->store->connection()->eval($lua, 1, $this->computeRedisKey(), $this->time);

        return $this;
    }

    /**
     * Compute the cache key for redis.
     *
     * @return string
     */
    protected function computeRedisKey()
    {
        return $this->store->getPrefix().$this->key;
    }
}
