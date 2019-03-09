# Laravel-Plugins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Demo/laravel-plugins.svg?style=flat-square)](https://packagist.org/packages/Demo/laravel-plugins)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Demo/laravel-plugins/master.svg?style=flat-square)](https://travis-ci.org/Demo/laravel-plugins)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/Demo/laravel-plugins.svg?maxAge=86400&style=flat-square)](https://scrutinizer-ci.com/g/Demo/laravel-plugins/?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/25320a08-8af4-475e-a23e-3321f55bf8d2.svg?style=flat-square)](https://insight.sensiolabs.com/projects/25320a08-8af4-475e-a23e-3321f55bf8d2)
[![Quality Score](https://img.shields.io/scrutinizer/g/Demo/laravel-plugins.svg?style=flat-square)](https://scrutinizer-ci.com/g/Demo/laravel-plugins)
[![Total Downloads](https://img.shields.io/packagist/dt/Demo/laravel-plugins.svg?style=flat-square)](https://packagist.org/packages/Demo/laravel-plugins)

| **Laravel**  |  **laravel-plugins** |
|---|---|
| 5.4  | ^1.0  |
| 5.5  | ^2.0  |
| 5.6  | ^3.0  |
| 5.7  | ^4.0  |

`Demo/laravel-plugins` is a Laravel package which created to manage your large Laravel app using plugins. Plugin is like a Laravel package, it has some views, controllers or models. This package is supported and tested in Laravel 5.

This package is a re-published, re-organised and maintained version of [pingpong/plugins](https://github.com/pingpong-labs/plugins), which isn't maintained anymore. This package is used in [AsgardCMS](https://asgardcms.com/).

With one big added bonus that the original package didn't have: **tests**.

Find out why you should use this package in the article: [Writing modular applications with laravel-plugins](https://nicolaswidart.com/blog/writing-modular-applications-with-laravel-plugins).

## Install

To install through Composer, by run the following command:

``` bash
composer require Demo/laravel-plugins
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Demo\Plugins\LaravelPluginsServiceProvider"
```

### Autoloading

By default the plugin classes are not loaded automatically. You can autoload your plugins using `psr-4`. For example:

``` json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Plugins\\": "Plugins/"
    }
  }
}
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://Demo.com/laravel-plugins/](https://Demo.com/laravel-plugins/).

## Credits

- [Maneash](https://github.com/Demo)
- [gravitano](https://github.com/gravitano)
- [All Contributors](../../contributors)

## About Maneash

Maneash is a freelance web developer specialising on the Laravel framework. View all my packages [on my website](https://nicolaswidart.com/projects).


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
