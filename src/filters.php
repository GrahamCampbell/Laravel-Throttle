<?php

/*
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

use GrahamCampbell\Throttle\Facades\Throttle;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

Route::filter('throttle', function ($route, $request, $limit = 10, $time = 60) {
    if (!Throttle::attempt($request, $limit, $time)) {
        throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
    }
});
