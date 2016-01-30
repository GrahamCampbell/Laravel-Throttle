<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle;

use GrahamCampbell\Throttle\Factories\CacheFactory;
use GrahamCampbell\Throttle\Factories\FactoryInterface;
use GrahamCampbell\Throttle\Transformers\TransformerFactory;
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
        $source = realpath(__DIR__.'/../config/throttle.php');

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
        $app->singleton('throttle.factory', function (Container $app) {
            $cache = $app->cache->driver($app->config->get('throttle.driver'));

            return new CacheFactory($cache);
        });

        $app->alias('throttle.factory', CacheFactory::class);
        $app->alias('throttle.factory', FactoryInterface::class);
    }

    /**
     * Register the transformer class.
     *
     * @return void
     */
    protected function registerTransformer()
    {
        $app->singleton('throttle.transformer', function () {
            return new TransformerFactory();
        });

        $app->alias('throttle.transformer', TransformerFactory::class);
    }

    /**
     * Register the throttle class.
     *
     * @return void
     */
    protected function registerThrottle()
    {
        $app->singleton('throttle', function (Container $app) {
            $factory = $app['throttle.factory'];
            $transformer = $app['throttle.transformer'];

            return new Throttle($factory, $transformer);
        });

        $app->alias('throttle', Throttle::class);
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
