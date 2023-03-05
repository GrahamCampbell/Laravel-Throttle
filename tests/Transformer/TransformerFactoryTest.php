<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Transformer;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Transformer\ArrayTransformer;
use GrahamCampbell\Throttle\Transformer\RequestTransformer;
use GrahamCampbell\Throttle\Transformer\TransformerFactory;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Mockery;

/**
 * This is the transformer factory test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class TransformerFactoryTest extends AbstractTestCase
{
    public function testRequest(): void
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make($request = Mockery::mock(Request::class));

        self::assertInstanceOf(RequestTransformer::class, $transformer);

        $request->shouldReceive('getClientIp')->once()->andReturn('123.123.123.123');
        $request->shouldReceive('path')->once()->andReturn('foobar');

        self::assertInstanceOf(Data::class, $transformer->transform($request, 123, 321));
    }

    public function testArray(): void
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make($array = ['ip' => 'abc', 'route' => 'qwerty']);

        self::assertInstanceOf(ArrayTransformer::class, $transformer);

        self::assertInstanceOf(Data::class, $transformer->transform($array, 123, 321));
    }

    public function testEmptyArray(): void
    {
        $factory = new TransformerFactory();
        $transformer = $factory->make([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The data array does not provide the required ip and route information.');

        $transformer->transform([]);
    }

    public function testError(): void
    {
        $factory = new TransformerFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('An array, or an instance of Illuminate\Http\Request was expected.');

        $factory->make(123);
    }
}
