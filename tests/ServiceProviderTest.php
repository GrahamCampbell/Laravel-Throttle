<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle;

use GrahamCampbell\TestBench\Traits\ServiceProviderTestCaseTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTestCaseTrait;

    public function testThrottleFactoryIsInjectable()
    {
        $this->assertIsInjectable('GrahamCampbell\Throttle\Factories\FactoryInterface');
    }

    public function testTransformerFactoryIsInjectable()
    {
        $this->assertIsInjectable('GrahamCampbell\Throttle\Transformers\TransformerFactory');
    }

    public function testThrottleIsInjectable()
    {
        $this->assertIsInjectable('GrahamCampbell\Throttle\Throttle');
    }
}
