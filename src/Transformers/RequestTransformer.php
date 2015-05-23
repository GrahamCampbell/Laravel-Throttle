<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Transformers;

use GrahamCampbell\Throttle\Data;

/**
 * This is the request transformer class.
 *
 * @author Graham Campbell <graham@cachethq.io>
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
