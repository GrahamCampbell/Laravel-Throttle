<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Throttlers;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Throttlers\CacheThrottler;
use Mockery;

/**
 * This is the cache throttler test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);

        $return = $throttler->count();

        $this->assertSame(1, $return);
    }

    public function testCountClear()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('put')->once()->with('abc', 0, 60);

        $return = $throttler->clear();

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);

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

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(11);

        $return = $throttler->count();

        $this->assertSame(11, $return);

        $return = $throttler->check();

        $this->assertTrue($return);
    }

    public function testCountCheckFalse()
    {
        $throttler = $this->getThrottler();

        $throttler->getStore()->shouldReceive('get')->once()->with('abc')->andReturn(12);

        $return = $throttler->count();

        $this->assertSame(12, $return);

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
        $store = Mockery::mock('Illuminate\Contracts\Cache\Store');
        $key = 'abc';
        $limit = 10;
        $time = 60;

        return new CacheThrottler($store, $key, $limit, $time);
    }
}
