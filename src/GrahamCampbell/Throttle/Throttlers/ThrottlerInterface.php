<?php

/**
 * This file is part of Laravel Throttle by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Throttle\Throttlers;

/**
 * This is the throttler interface class.
 *
 * @package    Laravel-Throttle
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Throttle/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Throttle
 */
interface ThrottlerInterface
{
    /**
     * Rate limit access to a resource.
     *
     * @return bool
     */
    public function attempt();

    /**
     * Hit the the throttle.
     *
     * @return $this
     */
    public function hit();

    /**
     * Get the throttle hit count.
     *
     * @return int
     */
    public function count();

    /**
     * Check the throttle.
     *
     * @return bool
     */
    public function check();
}
