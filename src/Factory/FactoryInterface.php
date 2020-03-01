<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Factory;

use GrahamCampbell\Throttle\Data;

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
     * @param \GrahamCampbell\Throttle\Data $data
     *
     * @return \GrahamCampbell\Throttle\Throttler\ThrottlerInterface
     */
    public function make(Data $data);
}
