# Changelog

All Notable changes to `laravel-plugins` will be documented in this file.

## Next

## 4.0.0 - 2018-09-30

### Added

- New way of handling routes by default using a RouteServiceProvider (instead of start.php)
- Laravel 5.7 support

### Changed

- Allow class resolution on short name and abstract
- `plugin:seed` accepts a `--class` option

## 3.3.1 - 2018-07-13

### Changed

- Added the ability to set a sub-namespace to controllers `plugin:make-controller Api\\TestController`

## 3.3.0 - 2018-06-21

### Changed

- `plugin:update` command has now the possibility to update all plugins at once
- Fixing commented code for Laravel Mix

## 3.2.0 - 2018-04-16

### Added

- Added possibility to update all plugins at once if any not specified (PR #523)

### Changed

- Mix: Fix css relative urls by changing the route folder (PR #521)
- Mix: Prevents every build from deleting previous Mix config file (PR #521)

## 3.1.0 - 2018-04-01

### Added

- Laravel mix configuration (https://Maneash.com/laravel-plugins/v3/basic-usage/compiling-assets)

### Changed

- Allow symlinks in plugin path
- Returns the parameter `--class` to the `SeedCommand`.
- Generate folders recursively
- Removing link that has become a 404
- Fixed seed command exception typehint

### Removed

- Removed the optimize command on the `plugin:make-migration` command

## 3.0.1 - 2018-02-16

### Changed

- Update publish commands to use the new API to get all enabled plugins (PR #483 )

## 3.0.0 - 2018-02-14

## Added

- Added support for laravel 5.6
- Using phpunit 7

## Changed

- **BC:** `Repository` class: renamed `enabled` to `allEnabled`
- **BC:** `Repository` class: renamed `disabled` to `allDisabled`
- **BC:** `Repository` class: renamed `active` to `enabled`
- **BC:** `Repository` class: renamed `notActive` to `disabled`

## Removed

- Dropped php 7.0 support
- **BC:** `Plugin` class: Deprecated `active()` method, use `enabled()`
- **BC:** `Plugin` class: Deprecated `notActive()` method, use `disabled()`
- **BC:** `Repository` class: Deprecated `addPath()` method, use `addLocation()`
- **BC:** `Repository` class: Deprecated `get()` method, use `find()`
- **BC:** `Repository` class: Deprecated `getUsed()` method, use `getUsedNow()`


## 2.7.0 - 2018-01-13

## Changed

- Rename the `before` method to `boot` in the `RouterServiceProvider` stub file
- Fixing caching issue if plugins were loaded from a different directory
- Fixing how plugins are loaded from vendor directory (#423 #417)
- Update to Mockery 1.0
- use default file stubs only if override does not exists
- Fix non well formed numeric value in seed command

## 2.6.0 - 2017-11-07

## Added

- Ability to customise the destination folder & namespace of a generated class
- Added `php artisan plugin:migrate-status` command
- `config_path()` helper for Lumen
- Added views tag to view config in ServiceProvider
- added package auto discovery for laravel 5.5 in generated plugin `composer.json`

## Changed

- Adding the ability to correctly load plugins from multiple locations, together
- Custom seeder path now also used in the `plugin:seed` command

## 2.5.1 - 2017-10-13

## Changed

- added config_path helper to helpers for Lumen support
- updated readme on how to install laravel-plugins in Lumen

## 2.5.0 - 2017-10-03

## Changed

- Making the path to migrations for `loadMigrationsFrom()` call dynamic based of configuration
- Making the factory path dynamic for master service provider & make-factory command
- Make the route file location dynamic in start.php based of `stubs.files.routes`
- Making the route path dynamic on the route service provider based of `stubs.files.routes`
- New structure in configuration to set which folders will be generated on `plugin:make` (old format still supported)
- Setting new sensible defaults to what folders to generate by default.
- Change the assets directory default location `resources/assets`

## 2.4.1 - 2017-09-27

## Changed

- Setting a default value for `plugins.paths.plugins` configuration key


## 2.4.0 - 2017-09-27

## Added

- New `plugin:make-resource` command to generate resource classes
- New `plugin:make-test` command to generate test classes

## Changed

- Improved error output of the `plugin:seed` command
- Marking methods that served as proxies in `Plugin` and `Repository` classes as deprecated for next major
- Fixed `plugin:make` and `plugin:make-provider` to generate the correct master service provider
- Tests: tests are now using `spatie/phpunit-snapshot-assertions` to make sure the generated files have the correct content
- Adding a sync option to the `plugin:make-job` command to make a synchronous job class
- Changed `plugin:make-event` command to allow duck typed events (not type hinted event class)
- Changed `plugin:make-listener` to allow a `--queued` option to make the event queueable
- Changed `plugin:make-listener` command to not use the full class typehint when class was previous imported

## 2.3.0 - 2017-09-26

## Added

- Ability to ignore some folders to generate
- Creating an plugin:unuse command to forget the previously saved plugin
- New command to generate Policy classes
- New command for creating factories
- New command for creating rules
- new `public_path` helper for Lumen

## Changed

- Refactored class names that generate something to be fully consistent

## 2.2.1 - 2017-09-14

## Changed

- Fixed class namespace to `Repository` in `ContractsServiceProvider`

## 2.2.0 - 2017-09-14

### Added

- Lumen compatibility with new way to register providers


## 2.1.0 - 2017-09-10

### Changed

- Register plugin migrations
- Fixed issue with `plugin:migrate-refresh` command
- Improved plugin loading of their service providers. Using laravel caching system. Allowing usage of deferred providers.
- Fixed path to plugin factories

## 2.0.0 - 2017-08-31

### Added

- Support Laravel 5.5


## 1.27.2 - 2017-08-29

### Changed

- Allow migrate-refresh command to be run without plugin argument
- Plugin name was added to the plugin enable and disable events

## 1.27.1 - 2017-07-31

### Changed

- Only run composer require on the plugin:update command if there's something to require
- Fixing lumen support

## 1.27.0 - 2017-07-19

### Added

- Laravel Lumen support

### Changed

- Update dev dependencies grumphp and phpcsfixer to latest versions
- The `make:model` command with the `-m` flag to create the associated migration is now using a current migration file name

## 1.26.0 - 2017-07-06

### Changed

- Throw an exception if asset name structure was not correct when using `{!! Plugin::asset() !!}`
- Create the plugin used file if non existent. Will provide for a better error message if plugin is omitted in console commands without a plugin:use.

## 1.25.1 - 2017-06-29

### Changed

- More flexibility to the `json()` method, while keeping the performance improvements.

## 1.25.0 - 2017-06-29

### Changed

- Improving performance by only instantiating Json class for the plugin.json file once
- Added support for generic git hosts

## 1.24.0 - 2017-06-12

### Changed

- Using `resource_path` to register plugin views
- Changed the method to load additional eloquent factory paths

## 1.23.0 - 2017-06-09

## Added

- A `--migration` flag to the `plugin:make-model` command to generate the migration file with a model
- Factories are now also defined in the master service providers. This is used in the `plugin:make` command without the `--plain` flag, or using `plugin:make-provider` with the `--master` flag.
- `plugin_path()` helper function.

### Changed

- The default location of event listeners is now in `Listeners/`, from `Events/Handlers`

## 1.22.0 - 2017-05-22

### Changed

- Fixed the `--plain` on the `make:plugin` command, to not include a service provider in the `plugin.json` file as it's not generated.
- Add command description to the `GenerateNotificationCommand`.

## 1.21.0 - 2017-05-10

### Added

- Added the `Macroable` trait to the `Plugin` class.

### Changed

- The `collections` method now accepts an optional parameter to get plugins by status, in a laravel collection.
- Allow laravel `5.5.*` to be used.


## 1.20.0 - 2017-04-19

### Changed

- `plugin:update`: Copy over the scripts key to main composer.json file
- Add a `--subpath` option to migrate command
- `plugin:update`: Install / require all require & require-dev package at once, instead of multiple calls to composer require.
- `plugin:publish-config` command now uses the namespace set up in the configuration file.

## 1.19.0 - 2017-03-16

### Changed

- `plugin:update` command now also takes the `require-dev` key into account
- Making the `$migrations` parameter optional on `getLastBatchNumber()`

## 1.18.0 - 2017-03-13

### Changed

- The plugin list command (`plugin:list`) now returns the native plugin name

## 1.17.1 - 2017-03-02

### Changed

- Fixed issues with route file location in `start.php`

## 1.17.0 - 2017-02-27

### Changed

- Add checking for failure to parse plugin JSON

## 1.16.0 - 2017-01-24

### Added

- Support for Laravel 5.4
- Adding show method on resource controller
- Added check for cached routes to not load them multiple times

## 1.15.0 - 2017-01-12

### Added

- Plugin requirements (PR #117)
- Added `Macroable` trait to `Plugin` class (PR #116)

### Changed

- Added missing import of the `Schema` facade on migration stubs
- A default plain migration will be used if the name was not matched against a predefined structure (create, add, delete and drop)
- Add tests for all the different migration structures above
- Fix: respecting order in reverse migrations (PR #98)
- Fix: `plugin:reset` and `plugin:migrate-rollback` didn't have `--database` option (PR #88)
- Fix: `Plugin::asset()`, removed obsolete backslash. (PR #91)

## 1.14.0 - 2016-10-19

### Added

- `plugin:make-notification` command to generate a notification class

### Changed

- Usage of the `lists()` method on the laravel collection has been removed in favor of `pluck()`
- Plugins can now overwrite the default migration and seed paths in the `plugin.json`  file

## 0.13.1 - 2016-09-09

### Changed

- Generated emails are now generated in the `Emails` folder by default

## 0.13.0 - 2016-09-08

### Changed

- Ability to set default value on the config() method of a plugin.
- Mail: Setting default value to config. Using that as the namespace.
- Using PSR2 for generated stubs


## 0.12.0 - 2016-09-08

### Added

- Generation of Mailable classes


## 0.11.2 - 2016-08-29

### Changed

- Using stable version of laravelcollective/html (5.3)

## 0.11.1 - 2016-08-25

### Changed

- Using stable development of laravelcollective/html


## 0.11 - 2016-08-24

### Added

- Adding `plugin:make-job` command to generate a job class
- Adding support for Laravel 5.3

### Changed

- Added force option to plugin:seed command

## 0.10 - 2016-08-10

### Added

- Experimental Laravel 5.3 support

### Changed

- Make sure the class name has `Controller` appended to it as well. Previously only the file had it suffixed.

### Removed

- Dependencies: `pingpong/support` and `pingpong/generators`

## 0.9 - 2016-07-30

### Added

- Adding a plain option to the generate controller command

### Changed

- Generate controller command now generates all resource methods

## 0.8 - 2016-07-28

### Fixed

- Plugin generation namespace now works with `StudlyCase` ([Issue #14](https://github.com/Maneash/laravel-plugins/issues/14))
- No plugin namespace fix (#13)

### Changed

- Using new service provider stub for plugin generation too

## 0.1 - 2016-06-27

Initial release
