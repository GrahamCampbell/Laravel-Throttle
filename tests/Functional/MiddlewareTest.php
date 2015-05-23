<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Throttle\Functional;

use GrahamCampbell\Tests\Throttle\AbstractTestCase;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the middleware test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class MiddlewareTest extends AbstractTestCase
{
    /**
     * Additional application environment setup.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function additionalSetup($app)
    {
        $app->config->set('throttle.driver', 'array');
    }

    /**
     * Run extra tear down code.
     *
     * @return void
     */
    protected function finish()
    {
        $this->app->cache->driver('array')->flush();
    }

    public function testBasicMiddlewareSuccess()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware', function () {
            return 'Why herro there!';
        }]);

        $this->hit(10);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testBasicMiddlewareFailure()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware', function () {
            return 'Why herro there!';
        }]);

        $this->hit(11);
    }

    public function testCustomLimitSuccess()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(5);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testCustomLimitFailure()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(6);
    }

    public function testCustomTimeSuccess()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:3,5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(3, 300);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testCustomTimeFailure()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:3,5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(4, 300);
    }

    protected function hit($times, $time = 3600)
    {
        for ($i = 0; $i < $times - 1; $i++) {
            $this->call('GET', 'throttle-test-route');
            $this->assertResponseOk();
        }

        try {
            $this->call('GET', 'throttle-test-route');
        } catch (TooManyRequestsHttpException $e) {
            $this->assertSame('Rate limit exceed.', $e->getMessage());
            $this->assertSame($time, $e->getHeaders()['Retry-After']);
            throw $e;
        }
    }
}
