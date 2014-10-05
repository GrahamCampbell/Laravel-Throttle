<?php

/**
 * This file is part of Laravel Throttle by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Throttle\Throttlers;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Throttlers\CacheThrottler;
use Mockery;

/**
 * This is the cache throttler test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
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
        $store = Mockery::mock('Illuminate\Cache\StoreInterface');
        $key = 'abc';
        $limit = 10;
        $time = 60;

        return new CacheThrottler($store, $key, $limit, $time);
    }
}
