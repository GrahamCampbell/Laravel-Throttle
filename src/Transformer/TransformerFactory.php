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

use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * This is the transformer factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class TransformerFactory implements TransformerFactoryInterface
{
    /**
     * Make a new transformer instance.
     *
     * @param mixed $data
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Throttle\Transformer\TransformerInterface
     */
    public function make($data)
    {
        if (is_object($data) && $data instanceof Request) {
            return new RequestTransformer();
        }

        if (is_array($data)) {
            return new ArrayTransformer();
        }

        throw new InvalidArgumentException(sprintf('An array, or an instance of %s was expected.', Request::class));
    }
}
