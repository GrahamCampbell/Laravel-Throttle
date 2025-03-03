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

namespace GrahamCampbell\Throttle\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the throttle middleware class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ThrottleMiddleware
{
    /**
     * Create a new throttle middleware instance.
     *
     * @param \GrahamCampbell\Throttle\Throttle $throttle
     *
     * @return void
     */
    public function __construct(
        private readonly Throttle $throttle,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param int|string               $limit
     * @param int|string               $time
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, int|string $limit = 10, int|string $time = 60): mixed
    {
        if (!$this->throttle->attempt($request, (int) $limit, (int) $time)) {
            throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceeded.');
        }

        return $next($request);
    }
}
