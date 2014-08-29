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
    public function testMake()
    {
        $throttle = $this->getFactory();

        $throttle->getCache()->shouldReceive('tags')
            ->with('throttle', '127.0.0.1')->once()->andReturn(Mockery::mock('Illuminate\Cache\StoreInterface'));

        $data = Mockery::mock('GrahamCampbell\Throttle\Data');
        $data->shouldReceive('getIp')->once()->andReturn('127.0.0.1');
        $data->shouldReceive('getRouteKey')->once()->andReturn('unique-md5-hash');
        $data->shouldReceive('getLimit')->once()->andReturn(246);
        $data->shouldReceive('getTime')->once()->andReturn(123);

        $return = $throttle->make($data);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    protected function getFactory()
    {
        $cache = Mockery::mock('Illuminate\Cache\Repository');

        return new CacheFactory($cache);
    }
}
