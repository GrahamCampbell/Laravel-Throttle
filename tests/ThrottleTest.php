<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle;

use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Factory\CacheFactory;
use GrahamCampbell\Throttle\Throttle;
use GrahamCampbell\Throttle\Throttler\CacheThrottler;
use GrahamCampbell\Throttle\Transformer\ArrayTransformer;
use GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface;
use Mockery;

/**
 * This is the throttle test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ThrottleTest extends AbstractTestBenchTestCase
{
    public function testMake()
    {
        extract($this->getThrottle());

        $return = $throttle->get($data, 12, 123);

        $this->assertInstanceOf(CacheThrottler::class, $return);
    }

    public function testCache()
    {
        extract($this->getThrottle());

        for ($i = 0; $i < 3; $i++) {
            $return = $throttle->get($data, 12, 123);
            $this->assertInstanceOf(CacheThrottler::class, $return);
        }
    }

    public function testCall()
    {
        extract($this->getThrottle());

        $throttler->shouldReceive('hit')->once()->andReturnSelf();

        $return = $throttle->hit($data, 12, 123);

        $this->assertInstanceOf(CacheThrottler::class, $return);
    }

    protected function getThrottle()
    {
        $factory = Mockery::mock(CacheFactory::class);

        $data = ['ip' => '127.0.0.1', 'route' => 'http://laravel.com/'];

        $throttler = Mockery::mock(CacheThrottler::class);

        $trans = Mockery::mock(ArrayTransformer::class);

        $transformer = Mockery::mock(TransformerFactoryInterface::class);

        $transformer->shouldReceive('make')->with($data)->andReturn($trans);
        $trans->shouldReceive('transform')->with($data, 12, 123)
            ->andReturn($transformed = new Data('127.0.0.1', 'http://laravel.com/', 12, 123));

        $throttle = new Throttle($factory, $transformer);

        $throttle->getFactory()->shouldReceive('make')->once()
            ->with($transformed)->andReturn($throttler);

        return compact('throttle', 'throttler', 'data', 'factory');
    }
}
