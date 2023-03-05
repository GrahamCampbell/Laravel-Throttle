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

namespace GrahamCampbell\Throttle;

/**
 * This is the data class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Data
{
    /**
     * The ip.
     *
     * @var string
     */
    private string $ip;

    /**
     * The route.
     *
     * @var string
     */
    private string $route;

    /**
     * The request limit.
     *
     * @var int
     */
    private int $limit;

    /**
     * The expiration time in minutes.
     *
     * @var int
     */
    private int $time;

    /**
     * The unique key.
     *
     * @var ?string
     */
    private ?string $key = null;

    /**
     * Create a new instance.
     *
     * @param string $ip
     * @param string $route
     * @param int    $limit
     * @param int    $time
     *
     * @return void
     */
    public function __construct(string $ip, string $route, int $limit = 10, int $time = 60)
    {
        $this->ip = $ip;
        $this->route = $route;
        $this->limit = $limit;
        $this->time = $time;
    }

    /**
     * Get the ip.
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Get the route.
     *
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * Get the request limit.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Get the expiration time in minutes.
     *
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * Get the unique key.
     *
     * This key is used to identify the data between requests.
     *
     * @return string
     */
    public function getKey(): string
    {
        if (!$this->key) {
            $this->key = sha1($this->ip.$this->route);
        }

        return $this->key;
    }
}
