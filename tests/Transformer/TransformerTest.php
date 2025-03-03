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
use GrahamCampbell\Throttle\Transformer\Transformer;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Mockery;

/**
 * This is the transformer test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class TransformerTest extends AbstractTestCase
{
    public function testRequest(): void
    {
        $transformer = new Transformer();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('getClientIp')->once()->andReturn('123.123.123.123');
        $request->shouldReceive('path')->once()->andReturn('foobar');

        self::assertInstanceOf(Data::class, $transformer->transform($request, 123, 321));
    }

    public function testArray(): void
    {
        $transformer = new Transformer();

        self::assertInstanceOf(Data::class, $transformer->transform(['ip' => 'abc', 'route' => 'qwerty'], 123, 321));
    }

    public function testEmptyArray(): void
    {
        $transformer = new Transformer();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The data array does not provide the required ip and route information.');

        self::assertInstanceOf(Data::class, $transformer->transform([], 123, 321));
    }
}
