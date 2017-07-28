<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Factories;

use GrahamCampbell\Throttle\DataInterface;

/**
 * This is the throttler factory interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface FactoryInterface
{
    /**
     * Make a new throttler instance.
     *
     * @param \GrahamCampbell\Throttle\DataInterface $data
     *
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function make(DataInterface $data);
}
