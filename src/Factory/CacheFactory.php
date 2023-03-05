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

namespace GrahamCampbell\Throttle\Factory;

use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Throttler\CacheThrottler;
use Illuminate\Contracts\Cache\Repository;

/**
 * This is the cache throttler factory class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class CacheFactory implements FactoryInterface
{
    /**
     * The cache instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    private Repository $cache;

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
     * @return \GrahamCampbell\Throttle\Throttler\CacheThrottler
     */
    public function make(Data $data): CacheThrottler
    {
        return new CacheThrottler($this->cache->getStore(), $data->getKey(), $data->getLimit(), $data->getTime() * 60);
    }
}
