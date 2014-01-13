<?php

/**
 * This file is part of Laravel Throttle by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Throttle\Classes;

use Mockery;
use GrahamCampbell\Throttle\Throttlers\CacheThrottler;
use GrahamCampbell\TestBench\Classes\AbstractTestCase;

/**
 * This is the cache throttler test class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
class CacheThrottlerTest extends AbstractTestCase
{
    public function testAttempt()
    {
        $throttler = $this->getThrottler();
        
        $throttler->getStore()->shouldReceive('add')->once()
            ->with('abc', 0, 60)->andReturn(true);
        $throttler->getStore()->shouldReceive('increment')->once()
            ->with('abc')->andReturn(1);

        $return = $throttler->attempt();

        $this->assertEquals(true, $return);
    }

    public function testCountHit()
    {
        $throttler = $this->getThrottler();
        
        $throttler->getStore()->shouldReceive('add')->once()
            ->with('abc', 0, 60)->andReturn(true);
        $throttler->getStore()->shouldReceive('increment')->once()
            ->with('abc')->andReturn(1);

        $throttler->hit();

        $return = $throttler->count();

        $this->assetEquals($return, 1);
    }

    public function testCountCheckTrue()
    {
        $throttler = $this->getThrottler();
        
        $throttler->getStore()->shouldReceive('get')->once()
            ->with('abc')->andReturn(null);

        $return = $throttler->count();

        $this->assetEquals($return, 0);

        $return = $throttler->check();

        $this->assetEquals($return, true);
    }

    public function testCountCheckFalse()
    {
        $throttler = $this->getThrottler();
        
        $throttler->getStore()->shouldReceive('get')->once()
            ->with('abc')->andReturn(11);

        $return = $throttler->count();

        $this->assetEquals($return, 11);

        $return = $throttler->check();

        $this->assetEquals($return, false);
    }

    protected function getThrottler()
    {
        $store = Mockery::mock('Illuminate\Cache\StoreInterface');
        $key = 'abc';
        $limit = 10;
        $time = 60;

        return new Throttler($store, $key, $limit, $time);
    }
}
