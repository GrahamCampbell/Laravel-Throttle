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

namespace GrahamCampbell\Tests\Throttle\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use GrahamCampbell\Tests\Throttle\AbstractTestCase;
use GrahamCampbell\Throttle\Facades\Throttle as Facade;
use GrahamCampbell\Throttle\Throttle;

/**
 * This is the throttle facade test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ThrottleTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'throttle';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return Facade::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected static function getFacadeRoot(): string
    {
        return Throttle::class;
    }
}
