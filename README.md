Laravel Throttle
================


[![Build Status](https://img.shields.io/travis/GrahamCampbell/Laravel-Throttle/master.svg?style=flat)](https://travis-ci.org/GrahamCampbell/Laravel-Throttle)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/GrahamCampbell/Laravel-Throttle.svg?style=flat)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Throttle/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/GrahamCampbell/Laravel-Throttle.svg?style=flat)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Throttle)
[![Software License](https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat)](LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/GrahamCampbell/Laravel-Throttle.svg?style=flat)](https://github.com/GrahamCampbell/Laravel-Throttle/releases)


## Introduction

Laravel Throttle was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a rate limiter for [Laravel 4.1+](http://laravel.com). Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Throttle/releases), [license](LICENSE.md), [api docs](http://docs.grahamjcampbell.co.uk), and [contribution guidelines](CONTRIBUTING.md).


## Installation

[PHP](https://php.net) 5.4.7+ or [HHVM](http://hhvm.com) 3.1+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Throttle, simply require `"graham-campbell/throttle": "~1.0"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Throttle is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'GrahamCampbell\Throttle\ThrottleServiceProvider'`

You can register the Throttle facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'Throttle' => 'GrahamCampbell\Throttle\Facades\Throttle'`


## Configuration

Laravel Throttle supports optional configuration.

To get started, first publish the package config file:

    php artisan config:publish graham-campbell/throttle

There is one config option:

**Cache Driver**

This option (`'driver'`) defines the cache driver to be used. It may be the name of any driver set in app/config/cache.php. Setting it to null will use the driver you have set as default in app/config/cache.php. Please note that a driver that supports cache tags is required. The default value for this setting is `null`.


## Usage

There is currently no usage documentation besides the [API Documentation](http://docs.grahamjcampbell.co.uk) for Laravel Throttle.

You may see an example of implementation in [Laravel Credentials](https://github.com/GrahamCampbell/Laravel-Credentials) and [Bootstrap CMS](https://github.com/GrahamCampbell/Bootstrap-CMS).


## License

Apache License

Copyright 2013-2014 Graham Campbell

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
