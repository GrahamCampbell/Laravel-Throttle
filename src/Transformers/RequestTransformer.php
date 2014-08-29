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

namespace GrahamCampbell\Throttle\Transformers;

use GrahamCampbell\Throttle\Data;
use Illuminate\Http\Request;

/**
 * This is the request transformer class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class RequestTransformer implements TransformerInterface
{
    /**
     * Transform the data into a new data instance.
     *
     * @param \Illuminate\Http\Request $data
     * @param int                      $limit
     * @param int                      $time
     *
     * @return \GrahamCampbell\Throttle\Data
     */
    public function transform($data, $limit = 10, $time = 60)
    {
        return new Data((string) $data->getClientIp(), (string) $data->path(), (int) $limit, (int) $time);
    }
}
