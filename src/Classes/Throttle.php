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

use Illuminate\Http\Request;
use Illuminate\Cache\Repository;

/**
 * This is the throttle class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
class Throttle
{
    /**
     * The cache instance.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * The throttler.
     *
     * @var string
     */
    protected $throttler;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Cache\Repository  $cache
     * @param  string  $throttler
     * @return void
     */
    public function __construct(Repository $cache, $throttler)
    {
        $this->cache = $cache;
        $this->throttler = $throttler;
    }

    /**
     * Get the throttler.
     *
     * @param  array|\Illuminate\Http\Request  $data
     * @param  int  $limit
     * @param  int  $time
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function get($data, $limit = 10, $time = 60)
    {
        $data = $this->parseData($data);
        $store = $this->getStore($data['ip']);
        $key = $this->getKey($data['route']);

        $throttler = $this->throttler;

        return new $throttler($store, $key, $limit, $time);
    }

    /**
     * Hit the the throttle.
     *
     * @param  array|\Illuminate\Http\Request  $data
     * @param  int  $limit
     * @param  int  $time
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function hit($data, $limit = 10, $time = 60)
    {
        return $this->get($data, $limit, $time)->hit();
    }

    /**
     * Clear the the throttle.
     *
     * @param  array|\Illuminate\Http\Request  $data
     * @param  int  $limit
     * @param  int  $time
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function clear($data, $limit = 10, $time = 60)
    {
        return $this->get($data, $limit, $time)->clear();
    }

    /**
     * Parse the data.
     *
     * @param  array|\Illuminate\Http\Request  $data
     * @return array
     */
    protected function parseData($data)
    {
        if ($data instanceof Request) {
            $parsed = array('ip' => $data->getClientIp(), 'route' => $data->path());
        } elseif (is_array($data)) {
            if (array_key_exists('ip', $data) && array_key_exists('route', $data)) {
                $parsed = array('ip' => $data['ip'], 'route' => $data['route']);
            }
        }

        if (!isset($parsed)) {
            throw new \InvalidArgumentException('An array, or an instance of Illuminate\Http\Request was expected.');
        }

        return $parsed;
    }

    /**
     * Get the store.
     *
     * @param  string  $ip
     * @return \Illuminate\Cache\TaggableStore
     */
    protected function getStore($ip)
    {
        return $this->cache->tags('throttle', $ip);
    }

    /**
     * Get the key.
     *
     * @param  string  $path
     * @return string
     */
    protected function getKey($path)
    {
        return md5($path);
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
