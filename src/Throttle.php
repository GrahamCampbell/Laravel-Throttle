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

namespace GrahamCampbell\Throttle;

use GrahamCampbell\Throttle\Factory\FactoryInterface;
use GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface;

/**
 * This is the throttle class.
 *
 * @method bool                                                  attempt(array|\Illuminate\Http\Request $data, int $limit, int $time)
 * @method \GrahamCampbell\Throttle\Throttler\ThrottlerInterface hit(array|\Illuminate\Http\Request $data, int $limit, int $time)
 * @method \GrahamCampbell\Throttle\Throttler\ThrottlerInterface clear(array|\Illuminate\Http\Request $data, int $limit, int $time)
 * @method int                                                   count(array|\Illuminate\Http\Request $data, int $limit, int $time)
 * @method bool                                                  check(array|\Illuminate\Http\Request $data, int $limit, int $time)
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Throttle
{
    /**
     * The cached throttler instances.
     *
     * @var \GrahamCampbell\Throttle\Throttler\ThrottlerInterface[]
     */
    protected $throttlers = [];

    /**
     * The throttle factory instance.
     *
     * @var \GrahamCampbell\Throttle\Factory\FactoryInterface
     */
    protected $factory;

    /**
     * The transformer factory instance.
     *
     * @var \GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface
     */
    protected $transformer;

    /**
     * Create a new instance.
     *
     * @param \GrahamCampbell\Throttle\Factory\FactoryInterface                $factory
     * @param \GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface $transformer
     *
     * @return void
     */
    public function __construct(FactoryInterface $factory, TransformerFactoryInterface $transformer)
    {
        $this->factory = $factory;
        $this->transformer = $transformer;
    }

    /**
     * Get a new throttler.
     *
     * @param mixed $data
     * @param int   $limit
     * @param int   $time
     *
     * @return \GrahamCampbell\Throttle\Throttler\ThrottlerInterface
     */
    public function get($data, int $limit = 10, int $time = 60)
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
     * @return \GrahamCampbell\Throttle\Factory\FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get the transformer factory instance.
     *
     * @return \GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface
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
    public function __call(string $method, array $parameters)
    {
        return $this->get(...$parameters)->$method();
    }
}
