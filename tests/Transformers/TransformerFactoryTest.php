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

namespace GrahamCampbell\Tests\Throttle\Transformers;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Transformers\TransformerFactory;
use Mockery;

/**
 * This is the transformer factory test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class TransformerFactoryTest extends AbstractTestCase
{
    public function testRequest()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make($request = Mockery::mock('Illuminate\Http\Request'));

        $this->assertInstanceOf('GrahamCampbell\Throttle\Transformers\RequestTransformer', $transformer);

        $request->shouldReceive('getClientIp')->once()->andReturn('123.123.123.123');
        $request->shouldReceive('path')->once()->andReturn('foobar');

        $this->assertInstanceOf('GrahamCampbell\Throttle\Data', $transformer->transform($request, 123, 321));
    }

    public function testArray()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make($array = ['ip' => 'abc', 'route' => 'qwerty']);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Transformers\ArrayTransformer', $transformer);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Data', $transformer->transform($array, 123, 321));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The data array does not provide the required ip and route information.
     */
    public function testEmptyArray()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make([]);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Transformers\ArrayTransformer', $transformer);

        $this->assertInstanceOf('GrahamCampbell\Throttle\Data', $transformer->transform([]));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage An array, or an instance of Illuminate\Http\Request was expected.
     */
    public function testError()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make(123);
    }
}
