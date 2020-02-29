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

/**
 * This is the transformer factory interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface TransformerFactoryInterface
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
    public function make($data);
}
