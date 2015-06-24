<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Throttlers;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Throttlers\CacheThrottler;
use Illuminate\Contracts\Cache\Store;
use Mockery;

/**
 * This is the cache throttler test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class CacheThrottlerTest extends AbstractTestCase
{
    public function testAttempt()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc');
        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 1, 60);

        $return = $throttler->attempt();

        $this->assertTrue($return);
    }

    public function testCountHit()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc');
        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 1, 60);

        $return = $throttler->hit();

        $this->assertInstanceOf(CacheThrottler::class, $return);

        $return = $throttler->count();

        $this->assertSame(1, $return);
    }

    public function testCountClear()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 0, 60);

        $return = $throttler->clear();

        $this->assertInstanceOf(CacheThrottler::class, $return);

        $return = $throttler->count();

        $this->assertSame(0, $return);
    }

    public function testCountCheckTrue()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc');

        $return = $throttler->count();

        $this->assertSame(0, $return);

        $return = $throttler->check();

        $this->assertTrue($return);
    }

    public function testCountCheckEdge()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(9);

        $return = $throttler->count();

        $this->assertSame(9, $return);

        $return = $throttler->check();

        $this->assertTrue($return);
    }

    public function testCountCheckFalse()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(10);

        $return = $throttler->count();

        $this->assertSame(10, $return);

        $return = $throttler->check();

        $this->assertFalse($return);
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
        $store = Mockery::mock(Store::class);
        $key = 'abc';
        $limit = 10;
        $time = 60;

        return new CacheThrottler($store, $key, $limit, $time);
    }
}
