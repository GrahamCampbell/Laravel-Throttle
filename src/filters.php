<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use GrahamCampbell\Throttle\Facades\Throttle;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

Route::filter('throttle', function ($route, $request, $limit = 10, $time = 60) {
    if (!Throttle::attempt($request, $limit, $time)) {
        throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
    }
});
