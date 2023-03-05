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

namespace GrahamCampbell\Tests\Throttle\Throttler;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Throttler\CacheThrottler;
use Illuminate\Contracts\Cache\Store;
use Mockery;

/**
 * This is the cache throttler test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class CacheThrottlerTest extends AbstractTestCase
{
    public function testAttempt(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('get')->once()->with('abc');
        $store->shouldReceive('put')->once()->with('abc', 1, 3600);

        self::assertTrue($throttler->attempt());
    }

    public function testCountHit(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('get')->once()->with('abc');
        $store->shouldReceive('put')->once()->with('abc', 1, 3600);

        self::assertInstanceOf(CacheThrottler::class, $throttler->hit());
        self::assertSame(1, $throttler->count());
    }

    public function testCountClear(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('put')->once()->with('abc', 0, 3600);

        self::assertInstanceOf(CacheThrottler::class, $throttler->clear());
        self::assertSame(0, $throttler->count());
    }

    public function testCountCheckTrue(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('get')->once()->with('abc');

        self::assertSame(0, $throttler->count());
        self::assertTrue($throttler->check());
    }

    public function testCountCheckEdge(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('get')->once()->with('abc')->andReturn(9);

        self::assertSame(9, $throttler->count());
        self::assertTrue($throttler->check());
    }

    public function testCountCheckFalse(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('get')->once()->with('abc')->andReturn(10);

        self::assertSame(10, $throttler->count());
        self::assertFalse($throttler->check());
    }

    public function testIsCountable(): void
    {
        $store = Mockery::mock(Store::class);

        $throttler = new CacheThrottler($store, 'abc', 10, 3600);

        $store->shouldReceive('get')->once()->with('abc')->andReturn(42);

        self::assertInstanceOf('Countable', $throttler);
        self::assertCount(42, $throttler);
    }
}
