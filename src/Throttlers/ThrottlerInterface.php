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

/**
 * This is the throttler interface class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface ThrottlerInterface
{
    /**
     * Rate limit access to a resource.
     *
     * @return bool
     */
    public function attempt();

    /**
     * Hit the throttle.
     *
     * @return $this
     */
    public function hit();

    /**
     * Clear the throttle.
     *
     * @return $this
     */
    public function clear();

    /**
     * Get the throttle hit count.
     *
     * @return int
     */
    public function count();

    /**
     * Check the throttle.
     *
     * @return bool
     */
    public function check();

    /**
     * Get the number of seconds until the "$this->key" is accessible again.
     *
     * @return int
     */
    public function availableIn();

    /**
     * Get the number of retries left for the given key.
     *
     * @return int
     */
    public function retriesLeft();
}
