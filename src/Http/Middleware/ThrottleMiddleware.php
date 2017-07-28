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

namespace GrahamCampbell\Throttle\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * This is the throttle middleware class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ThrottleMiddleware
{
    /**
     * The throttle instance.
     *
     * @var \GrahamCampbell\Throttle\Throttle
     */
    protected $throttle;

    /**
     * Create a new throttle middleware instance.
     *
     * @param \GrahamCampbell\Throttle\Throttle $throttle
     *
     * @return void
     */
    public function __construct(Throttle $throttle)
    {
        $this->throttle = $throttle;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param int                      $limit
     * @param int                      $time
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $limit = 10, $time = 60)
    {
        if (!$this->throttle->attempt($request, (int) $limit, (int) $time)) {
            throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceeded.');
        }

        return $next($request);
    }
}
