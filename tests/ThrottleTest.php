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

namespace GrahamCampbell\Tests\Throttle;

use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Factory\FactoryInterface;
use GrahamCampbell\Throttle\Throttle;
use GrahamCampbell\Throttle\Throttler\ThrottlerInterface;
use GrahamCampbell\Throttle\Transformer\ArrayTransformer;
use GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface;
use Mockery;

/**
 * This is the throttle test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ThrottleTest extends AbstractTestBenchTestCase
{
    public function testMake(): void
    {
        $factory = Mockery::mock(FactoryInterface::class);

        $data = ['ip' => '127.0.0.1', 'route' => 'http://laravel.com/'];

        $throttler = Mockery::mock(ThrottlerInterface::class);

        $trans = Mockery::mock(ArrayTransformer::class);

        $transformer = Mockery::mock(TransformerFactoryInterface::class);

        $transformer->shouldReceive('make')->with($data)->andReturn($trans);
        $trans->shouldReceive('transform')->with($data, 12, 123)
            ->andReturn($transformed = new Data('127.0.0.1', 'http://laravel.com/', 12, 123));

        $throttle = new Throttle($factory, $transformer);

        $factory->shouldReceive('make')->once()
            ->with($transformed)->andReturn($throttler);

        self::assertInstanceOf(ThrottlerInterface::class, $throttle->get($data, 12, 123));
    }

    public function testCache(): void
    {
        $factory = Mockery::mock(FactoryInterface::class);

        $data = ['ip' => '127.0.0.1', 'route' => 'http://laravel.com/'];

        $throttler = Mockery::mock(ThrottlerInterface::class);

        $trans = Mockery::mock(ArrayTransformer::class);

        $transformer = Mockery::mock(TransformerFactoryInterface::class);

        $transformer->shouldReceive('make')->with($data)->andReturn($trans);
        $trans->shouldReceive('transform')->with($data, 12, 123)
            ->andReturn($transformed = new Data('127.0.0.1', 'http://laravel.com/', 12, 123));

        $throttle = new Throttle($factory, $transformer);

        $factory->shouldReceive('make')->once()
            ->with($transformed)->andReturn($throttler);

        for ($i = 0; $i < 3; $i++) {
            $return = $throttle->get($data, 12, 123);
            self::assertInstanceOf(ThrottlerInterface::class, $return);
        }
    }

    public function testCall(): void
    {
        $factory = Mockery::mock(FactoryInterface::class);

        $data = ['ip' => '127.0.0.1', 'route' => 'http://laravel.com/'];

        $throttler = Mockery::mock(ThrottlerInterface::class);

        $trans = Mockery::mock(ArrayTransformer::class);

        $transformer = Mockery::mock(TransformerFactoryInterface::class);

        $transformer->shouldReceive('make')->with($data)->andReturn($trans);
        $trans->shouldReceive('transform')->with($data, 12, 123)
            ->andReturn($transformed = new Data('127.0.0.1', 'http://laravel.com/', 12, 123));

        $throttle = new Throttle($factory, $transformer);

        $factory->shouldReceive('make')->once()
            ->with($transformed)->andReturn($throttler);

        $throttler->shouldReceive('hit')->once()->andReturnSelf();

        $return = $throttle->hit($data, 12, 123);

        self::assertInstanceOf(ThrottlerInterface::class, $return);
    }
}
