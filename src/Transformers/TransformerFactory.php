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

namespace GrahamCampbell\Throttle\Transformers;

use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * This is the transformer factory class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class TransformerFactory
{
    /**
     * Make a new transformer instance.
     *
     * @param mixed $data
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Throttle\Transformers\TransformerInterface
     */
    public function make($data)
    {
        if (is_object($data) && $data instanceof Request) {
            return new RequestTransformer();
        }

        if (is_array($data)) {
            return new ArrayTransformer();
        }

        throw new InvalidArgumentException('An array, or an instance of Illuminate\Http\Request was expected.');
    }
}
