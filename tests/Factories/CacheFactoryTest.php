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

namespace GrahamCampbell\Tests\Throttle\Factories;

use Mockery;
use GrahamCampbell\Throttle\Factories\CacheFactory;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the cache factory test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class CacheThrottleTest extends AbstractTestCase
{
    public function testMakeRequest()
    {
        $throttle = $this->getFactory();

        $request = Mockery::mock('Illuminate\Http\Request');

        $throttle->getCache()->shouldReceive('tags')
            ->with('throttle', '127.0.0.1')->once()->andReturn(Mockery::mock('Illuminate\Cache\StoreInterface'));
        $request->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');
        $request->shouldReceive('path')->once()->andReturn('http://laravel.com/');

        $return = $throttle->make($request, 10, 60);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    public function testMakeArray()
    {
        $throttle = $this->getFactory();

        $array = array('ip' => '127.0.0.1', 'route' => 'http://laravel.com/');

        $throttle->getCache()->shouldReceive('tags')
            ->with('throttle', '127.0.0.1')->once()->andReturn(Mockery::mock('Illuminate\Cache\StoreInterface'));

        $return = $throttle->make($array, 10, 60);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMakeError()
    {
        $throttle = $this->getFactory();

        $array = array('error' => 'test');

        $throttle->make($array, 10, 60);
    }

    protected function getFactory()
    {
        $cache = Mockery::mock('Illuminate\Cache\Repository');

        return new CacheFactory($cache);
    }
}
