<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
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
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class CacheThrottler implements ThrottlerInterface, Countable
{
    /**
     * The store instance.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    private Store $store;

    /**
     * The key.
     *
     * @var string
     */
    private string $key;

    /**
     * The request limit.
     *
     * @var int
     */
    private int $limit;

    /**
     * The expiration time in seconds.
     *
     * @var int
     */
    private int $time;

    /**
     * The number of requests.
     *
     * @var int|null
     */
    private ?int $number = null;

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
    public function attempt(): bool
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
    public function hit(): self
    {
        if ($this->store instanceof RedisStore) {
            $this->hitRedis();
        } elseif ($this->count()) {
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
    public function clear(): self
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
    public function count(): int
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
    public function check(): bool
    {
        return $this->count() < $this->limit;
    }

    /**
     * An atomic hit implementation for redis.
     *
     * @return void
     */
    private function hitRedis(): void
    {
        $lua = 'local v = redis.call(\'incr\', KEYS[1]) '.
               'if v>1 then return v '.
               'else redis.call(\'setex\', KEYS[1], ARGV[1], 1) return 1 end';

        $this->number = $this->store->connection()->eval($lua, 1, $this->computeRedisKey(), $this->time);
    }

    /**
     * Compute the cache key for redis.
     *
     * @return string
     */
    protected function computeRedisKey(): string
    {
        return $this->store->getPrefix().$this->key;
    }
}
