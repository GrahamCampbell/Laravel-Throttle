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

namespace GrahamCampbell\Tests\Throttle\Throttler;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Throttler\CacheThrottler;
use Illuminate\Contracts\Cache\Store;
use Mockery;

/**
 * This is the cache throttler test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CacheThrottlerTest extends AbstractTestCase
{
    public function testAttempt()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc');
        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 1, 3600);

        $this->assertTrue($throttler->attempt());
    }

    public function testCountHit()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc');
        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 1, 3600);

        $this->assertInstanceOf(CacheThrottler::class, $throttler->hit());
        $this->assertSame(1, $throttler->count());
    }

    public function testCountClear()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 0, 3600);

        $this->assertInstanceOf(CacheThrottler::class, $throttler->clear());
        $this->assertSame(0, $throttler->count());
    }

    public function testCountCheckTrue()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc');

        $this->assertSame(0, $throttler->count());
        $this->assertTrue($throttler->check());
    }

    public function testCountCheckEdge()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(9);

        $this->assertSame(9, $throttler->count());
        $this->assertTrue($throttler->check());
    }

    public function testCountCheckFalse()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(10);

        $this->assertSame(10, $throttler->count());
        $this->assertFalse($throttler->check());
    }

    public function testIsCountable()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(42);

        $this->assertInstanceOf('Countable', $throttler);
        $this->assertCount(42, $throttler);
    }

    protected function getThrottler()
    {
        return new CacheThrottler(Mockery::mock(Store::class), 'abc', 10, 3600);
    }
}
