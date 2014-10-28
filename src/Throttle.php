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

namespace GrahamCampbell\Throttle;

use GrahamCampbell\Throttle\Factories\FactoryInterface;
use GrahamCampbell\Throttle\Transformers\TransformerFactory;

/**
 * This is the throttle class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2013-2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md> Apache 2.0
 */
class Throttle
{
    /**
     * The cached throttler instances.
     *
     * @var \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface[]
     */
    protected $throttlers = [];

    /**
     * The factory instance.
     *
     * @var \GrahamCampbell\Throttle\Factories\FactoryInterface
     */
    protected $factory;

    /**
     * The factory instance.
     *
     * @var \GrahamCampbell\Throttle\Transformers\TransformerFactory
     */
    protected $transformer;

    /**
     * Create a new instance.
     *
     * @param \GrahamCampbell\Throttle\Factories\FactoryInterface      $factory
     * @param \GrahamCampbell\Throttle\Transformers\TransformerFactory $transformer
     *
     * @return void
     */
    public function __construct(FactoryInterface $factory, TransformerFactory $transformer)
    {
        $this->factory = $factory;
        $this->transformer = $transformer;
    }

    /**
     * Get a new throttler.
     *
     * @param array|\Illuminate\Http\Request $data
     * @param int                            $limit
     * @param int                            $time
     *
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function get($data, $limit = 10, $time = 60)
    {
        $transformed = $this->transformer->make($data)->transform($data, $limit, $time);

        if (!array_key_exists($key = $transformed->getKey(), $this->throttlers)) {
            $this->throttlers[$key] = $this->factory->make($transformed);
        }

        return $this->throttlers[$key];
    }

    /**
     * Get the cache instance.
     *
     * @return \GrahamCampbell\Throttle\Factories\FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get the transformer instance.
     *
     * @codeCoverageIgnore
     *
     * @return \GrahamCampbell\Throttle\Transformers\TransformerFactory
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * Dynamically pass methods to a new throttler instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this, 'get'], $parameters)->$method();
    }
}
