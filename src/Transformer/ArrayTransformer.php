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

namespace GrahamCampbell\Throttle\Transformer;

use GrahamCampbell\Throttle\Data;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * This is the array transformer class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ArrayTransformer implements TransformerInterface
{
    /**
     * Transform the data into a new data instance.
     *
     * @param array $data
     * @param int   $limit
     * @param int   $time
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Throttle\Data
     */
    public function transform($data, int $limit = 10, int $time = 60)
    {
        if (($ip = Arr::get($data, 'ip')) && ($route = Arr::get($data, 'route'))) {
            return new Data((string) $ip, (string) $route, (int) $limit, (int) $time);
        }

        throw new InvalidArgumentException('The data array does not provide the required ip and route information.');
    }
}
