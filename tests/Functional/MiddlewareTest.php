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

namespace GrahamCampbell\Tests\Throttle\Functional;

use GrahamCampbell\Tests\Throttle\AbstractTestCase;
use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the middleware test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MiddlewareTest extends AbstractTestCase
{
    /**
     * Setup the application environment.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('throttle.driver', 'array');
    }

    /**
     * @after
     */
    public function tearDown(): void
    {
        $this->app->cache->driver('array')->flush();
    }

    public function testBasicMiddlewareSuccess()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class, function () {
            return 'Why herro there!';
        }]);

        $this->hit(10);
    }

    public function testBasicMiddlewareFailure()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class, function () {
            return 'Why herro there!';
        }]);

        $this->expectException(TooManyRequestsHttpException::class);

        $this->hit(11);
    }

    public function testCustomLimitSuccess()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(5);
    }

    public function testCustomLimitFailure()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':5', function () {
            return 'Why herro there!';
        }]);

        $this->expectException(TooManyRequestsHttpException::class);

        $this->hit(6);
    }

    public function testCustomTimeSuccess()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':3,5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(3, 300);
    }

    public function testCustomTimeFailure()
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':3,5', function () {
            return 'Why herro there!';
        }]);

        $this->expectException(TooManyRequestsHttpException::class);

        $this->hit(4, 300);
    }

    protected function hit($times, $time = 3600)
    {
        for ($i = 0; $i < $times - 1; $i++) {
            $this->wrappedCall('GET', 'throttle-test-route');
        }

        try {
            $this->wrappedCall('GET', 'throttle-test-route');
        } catch (TooManyRequestsHttpException $e) {
            $this->assertSame('Rate limit exceeded.', $e->getMessage());
            $this->assertSame($time, $e->getHeaders()['Retry-After']);

            throw $e;
        }
    }

    protected function wrappedCall($method, $uri)
    {
        $response = $this->call($method, $uri);

        if ($ex = $response->exception) {
            throw $ex;
        }

        $this->assertSame(200, $response->status());
    }
}
