Laravel Throttle
================


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/GrahamCampbell/Laravel-Throttle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
[![Build Status](https://travis-ci.org/GrahamCampbell/Laravel-Throttle.png)](https://travis-ci.org/GrahamCampbell/Laravel-Throttle)
[![Coverage Status](https://coveralls.io/repos/GrahamCampbell/Laravel-Throttle/badge.png)](https://coveralls.io/r/GrahamCampbell/Laravel-Throttle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Throttle/badges/quality-score.png?s=6f8f984d8c0da418482f66edd9b3462ad39ff2d3)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Throttle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2d8940a5-38f0-4d35-be65-20b49d1a33c9/mini.png)](https://insight.sensiolabs.com/projects/2d8940a5-38f0-4d35-be65-20b49d1a33c9)
[![Software License](https://poser.pugx.org/graham-campbell/throttle/license.png)](https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md)
[![Latest Version](https://poser.pugx.org/graham-campbell/throttle/v/stable.png)](https://packagist.org/packages/graham-campbell/throttle)


## What Is Laravel Throttle?

Laravel Throttle is a rate limiter for [Laravel 4.1](http://laravel.com).

* Laravel Throttle was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell).
* Laravel Throttle uses [Travis CI](https://travis-ci.org/GrahamCampbell/Laravel-Throttle) with [Coveralls](https://coveralls.io/r/GrahamCampbell/Laravel-Throttle) to check everything is working.
* Laravel Throttle uses [Scrutinizer CI](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Throttle) and [SensioLabsInsight](https://insight.sensiolabs.com/projects/2d8940a5-38f0-4d35-be65-20b49d1a33c9) to run additional checks.
* Laravel Throttle uses [Composer](https://getcomposer.org) to load and manage dependencies.
* Laravel Throttle provides a [change log](https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Throttle/releases), and [api docs](http://grahamcampbell.github.io/Laravel-Throttle).
* Laravel Throttle is licensed under the Apache License, available [here](https://github.com/GrahamCampbell/Laravel-Throttle/blob/master/LICENSE.md).


## System Requirements

* PHP 5.4.7+ or PHP 5.5+ is required.
* You will need [Laravel 4.1](http://laravel.com) because this package is designed for it.
* You will need [Composer](https://getcomposer.org) installed to load the dependencies of Laravel Throttle.


## Installation

Please check the system requirements before installing Laravel Throttle.

To get the latest version of Laravel Throttle, simply require it in your `composer.json` file.

`"graham-campbell/throttle": "*"`

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Throttle is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'GrahamCampbell\Throttle\ThrottleServiceProvider'`

You can register the Throttle facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'Throttle' => 'GrahamCampbell\Throttle\Facades\Throttle'`


## Configuration

Laravel Throttle supports optional configuration.

To get started, first publish the package config file:

    php artisan config:publish graham-campbell/throttle

There is one config option:

**Throttler Class**

This option (`'throttler'`) defines the throttler class to be used. The default value for this setting is `'GrahamCampbell\Throttle\Throttlers\CacheThrottler'`.


## Usage

There is currently no usage documentation besides the [API Documentation](http://grahamcampbell.github.io/Laravel-Throttle
) for Laravel Throttle.

You may see an example of implementation in [Laravel Credentials](https://github.com/GrahamCampbell/Laravel-Credentials) and [CMS Core](https://github.com/GrahamCampbell/CMS-Core).


## Updating Your Fork

Before submitting a pull request, you should ensure that your fork is up to date.

You may fork Laravel Throttle:

    git remote add upstream git://github.com/GrahamCampbell/Laravel-Throttle.git

The first command is only necessary the first time. If you have issues merging, you will need to get a merge tool such as [P4Merge](http://perforce.com/product/components/perforce_visual_merge_and_diff_tools).

You can then update the branch:

    git pull --rebase upstream master
    git push --force origin <branch_name>

Once it is set up, run `git mergetool`. Once all conflicts are fixed, run `git rebase --continue`, and `git push --force origin <branch_name>`.


## Pull Requests

Please review these guidelines before submitting any pull requests.

* When submitting bug fixes, check if a maintenance branch exists for an older series, then pull against that older branch if the bug is present in it.
* Before sending a pull request for a new feature, you should first create an issue with [Proposal] in the title.
* Please follow the [PSR-2 Coding Style](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) and [PHP-FIG Naming Conventions](https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-naming-conventions.md).


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
