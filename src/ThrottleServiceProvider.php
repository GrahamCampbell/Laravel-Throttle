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

use GrahamCampbell\Throttle\Factory\CacheFactory;
use GrahamCampbell\Throttle\Factory\FactoryInterface;
use GrahamCampbell\Throttle\Transformer\TransformerFactory;
use GrahamCampbell\Throttle\Transformer\TransformerFactoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the throttle service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ThrottleServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/throttle.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('throttle.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('throttle');
        }

        $this->mergeConfigFrom($source, 'throttle');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();
        $this->registerTransformer();
        $this->registerThrottle();
    }

    /**
     * Register the factory class.
     *
     * @return void
     */
    protected function registerFactory()
    {
        $this->app->singleton('throttle.factory', function (Container $app) {
            $cache = $app->cache->driver($app->config->get('throttle.driver'));

            return new CacheFactory($cache);
        });

        $this->app->alias('throttle.factory', CacheFactory::class);
        $this->app->alias('throttle.factory', FactoryInterface::class);
    }

    /**
     * Register the transformer class.
     *
     * @return void
     */
    protected function registerTransformer()
    {
        $this->app->singleton('throttle.transformer', function () {
            return new TransformerFactory();
        });

        $this->app->alias('throttle.transformer', TransformerFactory::class);
        $this->app->alias('throttle.transformer', TransformerFactoryInterface::class);
    }

    /**
     * Register the throttle class.
     *
     * @return void
     */
    protected function registerThrottle()
    {
        $this->app->singleton('throttle', function (Container $app) {
            $factory = $app['throttle.factory'];
            $transformer = $app['throttle.transformer'];

            return new Throttle($factory, $transformer);
        });

        $this->app->alias('throttle', Throttle::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'throttle',
            'throttle.factory',
            'throttle.transformer',
        ];
    }
}
