<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Throttlers;

use Countable;
use Illuminate\Contracts\Cache\Repository;

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
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

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
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param string                            $key
     * @param int                               $limit
     * @param int                               $time
     *
     * @return void
     */
    public function __construct(Repository $cache, $key, $limit, $time)
    {
        $this->cache = $cache;
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
        if ($this->count()) {
            $this->cache->increment($this->key);
            $this->number++;
        }
        else {
            $this->setFirstHitData();
            $this->cache->put($this->key, 1, $this->time);
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

        $this->cache->put($this->key, $this->number, $this->time);

        $this->cache->forget($this->key . ":" . $this->getFirstHitMarker());

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

        $this->number = (int) $this->cache->get($this->key);

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
        if(!$this->cache->has($this->key . ":" . $this->getFirstHitMarker())){
            $this->clear();
        }

        if($this->limitRemainedTime() <= 0){
            $this->clear();
        }

        if($this->count() < $this->limit){
            return true;
        }

        return false;
    }

    protected function limitRemainedTime()
    {
        return ($this->time * 60) - (time() - $this->cache->get($this->key . ":" . $this->getFirstHitMarker()));
    }

    /**
     * Get the store instance.
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Get the Cache First Hit marker.
     *
     * @return string
     */
    protected function getFirstHitMarker()
    {
        return (property_exists($this, 'firstHitMarker')) ? $this->firstHitMarker : 'marker';
    }

    /**
     * Set the First Hit cache data.
     *
     * @return void
     */
    protected function setFirstHitData()
    {
        $this->cache->add($this->key . ":" . $this->getFirstHitMarker(), time(), $this->time);
    }

    /**
     * Get the number of seconds until the "$this->key" is accessible again.
     *
     * @return int
     */
    public function availableIn()
    {
        if($this->check()){
            return 0;
        }

        return $this->limitRemainedTime();
    }

    /**
     * Get the number of retries left for the given key.
     *
     * @return int
     */
    public function retriesLeft()
    {
        $remainedAttempts = $this->limit - $this->count();

        return ($remainedAttempts > 0) ? $remainedAttempts : 0;
    }
}
