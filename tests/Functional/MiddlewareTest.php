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

namespace GrahamCampbell\Tests\Throttle\Functional;

use GrahamCampbell\Tests\Throttle\AbstractTestCase;
use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the middleware test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
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
    protected function getEnvironmentSetUp($app): void
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

    public function testBasicMiddlewareSuccess(): void
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class, function () {
            return 'Why herro there!';
        }]);

        $this->hit(10);
    }

    public function testBasicMiddlewareFailure(): void
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class, function () {
            return 'Why herro there!';
        }]);

        $this->expectException(TooManyRequestsHttpException::class);

        $this->hit(11);
    }

    public function testCustomLimitSuccess(): void
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(5);
    }

    public function testCustomLimitFailure(): void
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':5', function () {
            return 'Why herro there!';
        }]);

        $this->expectException(TooManyRequestsHttpException::class);

        $this->hit(6);
    }

    public function testCustomTimeSuccess(): void
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':3,5', function () {
            return 'Why herro there!';
        }]);

        $this->hit(3, 300);
    }

    public function testCustomTimeFailure(): void
    {
        $this->app->router->get('throttle-test-route', ['middleware' => ThrottleMiddleware::class.':3,5', function () {
            return 'Why herro there!';
        }]);

        $this->expectException(TooManyRequestsHttpException::class);

        $this->hit(4, 300);
    }

    private function hit(int $times, int $time = 3600): void
    {
        for ($i = 0; $i < $times - 1; $i++) {
            $this->wrappedCall('GET', 'throttle-test-route');
        }

        try {
            $this->wrappedCall('GET', 'throttle-test-route');
        } catch (TooManyRequestsHttpException $e) {
            self::assertSame('Rate limit exceeded.', $e->getMessage());
            self::assertSame($time, $e->getHeaders()['Retry-After']);

            throw $e;
        }
    }

    private function wrappedCall(string $method, string $uri): void
    {
        $response = $this->call($method, $uri);

        if ($ex = $response->exception) {
            throw $ex;
        }

        self::assertSame(200, $response->status());
    }
}
