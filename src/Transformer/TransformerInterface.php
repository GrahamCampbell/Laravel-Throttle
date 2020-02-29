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
 * This is the transformer interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface TransformerInterface
{
    /**
     * Transform the data into a new data instance.
     *
     * @param array|\Illuminate\Http\Request $data
     * @param int                            $limit
     * @param int                            $time
     *
     * @return \GrahamCampbell\Throttle\Data
     */
    public function transform($data, int $limit = 10, int $time = 60);
}
