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

namespace GrahamCampbell\Tests\Throttle;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use GrahamCampbell\Throttle\Factory\CacheFactory;
use GrahamCampbell\Throttle\Factory\FactoryInterface;
use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware;
use GrahamCampbell\Throttle\Throttle;
use GrahamCampbell\Throttle\Transformer\TransformerFactory;
use GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testCacheFactoryIsInjectable(): void
    {
        $this->assertIsInjectable(CacheFactory::class);
        $this->assertIsInjectable(FactoryInterface::class);
    }

    public function testTransformerFactoryIsInjectable(): void
    {
        $this->assertIsInjectable(TransformerFactory::class);
        $this->assertIsInjectable(TransformerFactoryInterface::class);
    }

    public function testThrottleIsInjectable(): void
    {
        $this->assertIsInjectable(Throttle::class);
    }

    public function testMiddlewareIsInjectable(): void
    {
        $this->assertIsInjectable(ThrottleMiddleware::class);
    }
}
