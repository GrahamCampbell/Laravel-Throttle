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

namespace GrahamCampbell\Tests\Throttle\Factory;

use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Throttle\Data;
use GrahamCampbell\Throttle\Factory\CacheFactory;
use GrahamCampbell\Throttle\Throttler\CacheThrottler;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Mockery;

/**
 * This is the cache factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CacheFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $throttle = $this->getFactory();

        $throttle->getCache()->shouldReceive('getStore')
            ->once()->andReturn(Mockery::mock(Store::class));

        $data = Mockery::mock(Data::class);
        $data->shouldReceive('getKey')->once()->andReturn('unique-hash');
        $data->shouldReceive('getLimit')->once()->andReturn(246);
        $data->shouldReceive('getTime')->once()->andReturn(123);

        $return = $throttle->make($data);

        $this->assertInstanceOf(CacheThrottler::class, $return);
    }

    protected function getFactory()
    {
        $cache = Mockery::mock(Repository::class);

        return new CacheFactory($cache);
    }
}
