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

namespace GrahamCampbell\Throttle\Throttlers;

use Illuminate\Contracts\Cache\Store;
use ReflectionClass;

class LifetimeHelper
{
    /**
     * Determine the lifetime.
     *
     * @param int $minutes
     *
     * @return int
     */
    public static function computeLifetime(int $minutes)
    {
        return static::isLegacy() ? $minutes : $minutes * 60;
    }

    /**
     * Determine if the cache store is legacy.
     *
     * @return bool
     */
    public static function isLegacy()
    {
        static $legacy;

        if ($legacy === null) {
            $params = (new ReflectionClass(Store::class))->getMethod('put')->getParameters();
            $legacy = $params[2]->getName() === 'minutes';
        }

        return $legacy;
    }
}
