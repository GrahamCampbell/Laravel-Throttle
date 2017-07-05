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
 * This is the data interface.
 *
 * @author Matt Beale <matt@heyratfans.co.uk>
 */
interface DataInterface
{
    /**
     * Get the request limit.
     *
     * @return int
     */
    public function getLimit();

    /**
     * Get the expiration time.
     *
     * @return int
     */
    public function getTime();

    /**
     * Get the unique key.
     *
     * This key is used to identify the data between requests.
     *
     * @return string
     */
    public function getKey();
}
