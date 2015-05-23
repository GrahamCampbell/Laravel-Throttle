<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Throttlers;

/**
 * This is the throttler interface class.
 *
 * @author Graham Campbell <graham@cachethq.io>
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
}
