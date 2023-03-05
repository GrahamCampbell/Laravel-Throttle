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

/**
 * This is the throttler interface class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
interface ThrottlerInterface
{
    /**
     * Rate limit access to a resource.
     *
     * @return bool
     */
    public function attempt(): bool;

    /**
     * Hit the throttle.
     *
     * @return $this
     */
    public function hit(): self;

    /**
     * Clear the throttle.
     *
     * @return $this
     */
    public function clear(): self;

    /**
     * Get the throttle hit count.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Check the throttle.
     *
     * @return bool
     */
    public function check(): bool;
}
