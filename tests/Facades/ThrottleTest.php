<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Facades;

use GrahamCampbell\Throttle\Facades\Throttle as Facade;
use GrahamCampbell\Throttle\Throttle;
use GrahamCampbell\TestBenchCore\FacadeTrait;
use GrahamCampbell\Tests\Throttle\AbstractTestCase;

/**
 * This is the throttle facade test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class ThrottleTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'throttle';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return Facade::class;
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return Throttle::class;
    }
}
