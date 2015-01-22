<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Factories;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Factories\CacheFactory;
use Mockery;

/**
 * This is the cache factory test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CacheFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $throttle = $this->getFactory();

        $throttle->getCache()->shouldReceive('tags')
            ->with('throttle', '127.0.0.1')->once()->andReturn(Mockery::mock('Illuminate\Contracts\Cache\Store'));

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
        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');

        return new CacheFactory($cache);
    }
}
