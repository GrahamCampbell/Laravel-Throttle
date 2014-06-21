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

namespace GrahamCampbell\Tests\Throttle;

use Mockery;
use GrahamCampbell\Throttle\Throttle;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;

/**
 * This is the throttle test class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
class ThrottleTest extends AbstractTestBenchTestCase
{
    public function testMake()
    {
        extract($this->getThrottle());

        $return = $throttle->get($request, 12, 123);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    public function testCall()
    {
        extract($this->getThrottle());

        $throttler->shouldReceive('hit')->once()->andReturnSelf();

        $return = $throttle->hit($request, 12, 123);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Throttlers\CacheThrottler', $return);
    }

    protected function getThrottle()
    {
        $factory = Mockery::mock('GrahamCampbell\Throttle\Factories\CacheFactory');

        $request = Mockery::mock('Illuminate\Http\Request');

        $throttler = Mockery::mock('GrahamCampbell\Throttle\Throttlers\CacheThrottler');

        $throttle = new Throttle($factory);

        $throttle->getFactory()->shouldReceive('make')->once()
            ->with($request, 12, 123)->andReturn($throttler);

        return compact('throttle', 'throttler', 'request', 'factory');
    }
}
