<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Factories;

use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Throttlers\CacheThrottler;
use Illuminate\Contracts\Cache\Repository;

/**
 * This is the cache throttler factory class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CacheFactory implements FactoryInterface
{
    /**
     * The cache instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
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
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getCache()
    {
        return $this->cache;
    }
}
