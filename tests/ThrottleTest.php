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

namespace GrahamCampbell\Tests\Throttle;

use Mockery;
use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Throttle;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;

/**
 * This is the throttle test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class ThrottleTest extends AbstractTestBenchTestCase
{
    public function testMake()
    {
        extract($this->getThrottle());

        $return = $throttle->get($data, 12, 123);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    public function testCache()
    {
        extract($this->getThrottle());

        for ($i = 0; $i < 3; $i++) {
            $return = $throttle->get($data, 12, 123);
            $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
        }
    }

    public function testCall()
    {
        extract($this->getThrottle());

        $throttler->shouldReceive('hit')->once()->andReturnSelf();

        $return = $throttle->hit($data, 12, 123);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    protected function getThrottle()
    {
        $factory = Mockery::mock('GrahamCampbell\Throttle\Factories\CacheFactory');

        $data = array('ip' => '127.0.0.1', 'route' => 'http://laravel.com/');

        $throttler = Mockery::mock('GrahamCampbell\Throttle\Throttlers\CacheThrottler');

        $trans = Mockery::mock('GrahamCampbell\Throttle\Transformers\ArrayTransformer');

        $transformer = Mockery::mock('GrahamCampbell\Throttle\Transformers\TransformerFactory');

        $transformer->shouldReceive('make')->with($data)->andReturn($trans);
        $trans->shouldReceive('transform')->with($data, 12, 123)
            ->andReturn($transformed = new Data('127.0.0.1', 'http://laravel.com/', 12, 123));

        $throttle = new Throttle($factory, $transformer);

        $throttle->getFactory()->shouldReceive('make')->once()
            ->with($transformed)->andReturn($throttler);

        return compact('throttle', 'throttler', 'data', 'factory');
    }
}
