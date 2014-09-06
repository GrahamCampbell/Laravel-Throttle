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

namespace GrahamCampbell\Tests\Throttle\Functional;

use GrahamCampbell\Tests\Throttle\AbstractTestCase;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the filter test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class FilterTest extends AbstractTestCase
{
    /**
     * Specify if routing filters are enabled.
     *
     * @return bool
     */
    protected function enableFilters()
    {
        return true;
    }

    /**
     * Additional application environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function additionalSetup($app)
    {
        $app['config']->set('graham-campbell/throttle::driver', 'array');
    }

    /**
     * Run extra tear down code.
     *
     * @return void
     */
    protected function finish()
    {
        $this->app['cache']->driver('array')->flush();
    }

    public function testBasicFilterSuccess()
    {
        $this->app['router']->get('throttle-test-route', array('before' => 'throttle', function () {
            return 'Why herro there!';
        }));

        $this->hit(10);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testBasicFilterFailure()
    {
        $this->app['router']->get('throttle-test-route', array('before' => 'throttle', function () {
            return 'Why herro there!';
        }));

        $this->hit(11);
    }

    public function testCustomLimitSuccess()
    {
        $this->app['router']->get('throttle-test-route', array('before' => 'throttle:5', function () {
            return 'Why herro there!';
        }));

        $this->hit(5);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testCustomLimitFailure()
    {
        $this->app['router']->get('throttle-test-route', array('before' => 'throttle:5', function () {
            return 'Why herro there!';
        }));

        $this->hit(6);
    }

    public function testCustomTimeSuccess()
    {
        $this->app['router']->get('throttle-test-route', array('before' => 'throttle:3,5', function () {
            return 'Why herro there!';
        }));

        $this->hit(3, 300);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testCustomTimeFailure()
    {
        $this->app['router']->get('throttle-test-route', array('before' => 'throttle:3,5', function () {
            return 'Why herro there!';
        }));

        $this->hit(4, 300);
    }

    protected function hit($times, $time = 3600)
    {
        // echo "\n";

        for ($i = 0; $i < $times; $i++) {
            // echo "hit\n";
            $this->call('GET', 'throttle-test-route');
            $this->assertResponseOk();
        }

        // echo "done\n";

        try {
            $this->call('GET', 'throttle-test-route');
        } catch (TooManyRequestsHttpException $e) {
            $this->assertSame('Rate limit exceed.', $e->getMessage());
            $this->assertSame($time, $e->getHeaders()['Retry-After']);
            throw $e;
        }
    }
}
