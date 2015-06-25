<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Transformers;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Transformers\ArrayTransformer;
use GrahamCampbell\Throttle\Transformers\RequestTransformer;
use GrahamCampbell\Throttle\Transformers\TransformerFactory;
use Illuminate\Http\Request;
use Mockery;

/**
 * This is the transformer factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class TransformerFactoryTest extends AbstractTestCase
{
    public function testRequest()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make($request = Mockery::mock(Request::class));

        $this->assertInstanceOf(RequestTransformer::class, $transformer);

        $request->shouldReceive('getClientIp')->once()->andReturn('123.123.123.123');
        $request->shouldReceive('path')->once()->andReturn('foobar');

        $this->assertInstanceOf(Data::class, $transformer->transform($request, 123, 321));
    }

    public function testArray()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make($array = ['ip' => 'abc', 'route' => 'qwerty']);

        $this->assertInstanceOf(ArrayTransformer::class, $transformer);

        $this->assertInstanceOf(Data::class, $transformer->transform($array, 123, 321));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The data array does not provide the required ip and route information.
     */
    public function testEmptyArray()
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make([]);

        $this->assertInstanceOf(ArrayTransformer::class, $transformer);

        $this->assertInstanceOf(Data::class, $transformer->transform([]));
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
