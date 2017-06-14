<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle;

/**
 * This is the data class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Data
{
    /**
     * The ip.
     *
     * @var string
     */
    protected $ip;

    /**
     * The route.
     *
     * @var string
     */
    protected $route;
    
    /**
     * The user
     * 
     * @var string
     */
    protected $user;
    

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
     * The unique key.
     *
     * @var string
     */
    protected $key;

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
    public function __construct($ip, $route, $user, $limit = 10, $time = 60)
    {
        $this->ip = $ip;
        $this->route = $route;
        $this->user = $user;
        $this->limit = $limit;
        $this->time = $time;
    }

    /**
     * Get the ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the route.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get the user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Get the request limit.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Get the expiration time.
     *
     * @return int
     */
    public function getTime()
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
    public function getKey()
    {
        if (!$this->key) {
            $this->key = sha1($this->ip.$this->route.$this->user);
        }

        return $this->key;
    }
}
