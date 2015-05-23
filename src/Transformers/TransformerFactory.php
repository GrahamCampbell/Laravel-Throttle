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

use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * This is the transformer factory class.
 *
 * @author Graham Campbell <graham@cachethq.io>
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
