<?php

namespace Maneash\Plugins;

use Countable;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Maneash\Plugins\Contracts\RepositoryInterface;
use Maneash\Plugins\Exceptions\InvalidAssetPath;
use Maneash\Plugins\Exceptions\PluginNotFoundException;
use Maneash\Plugins\Process\Installer;
use Maneash\Plugins\Process\Updater;

abstract class FileRepository implements RepositoryInterface, Countable
{
    use Macroable;

    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The plugin path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * @var string
     */
    protected $stubPath;

    /**
     * The constructor.
     *
     * @param Container $app
     * @param string|null $path
     */
    public function __construct(Container $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
    }

    /**
     * Add other plugin location.
     *
     * @param string $path
     *
     * @return $this
     */
    public function addLocation($path)
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Get all additional paths.
     *
     * @return array
     */
    public function getPaths() : array
    {
        return $this->paths;
    }

    /**
     * Get scanned plugins paths.
     *
     * @return array
     */
    public function getScanPaths() : array
    {
        $paths = $this->paths;

        $paths[] = $this->getPath();

        if ($this->config('scan.enabled')) {
            $paths = array_merge($paths, $this->config('scan.paths'));
        }

        $paths = array_map(function ($path) {
            return ends_with($path, '/*') ? $path : str_finish($path, '/*');
        }, $paths);

        return $paths;
    }

    /**
     * Creates a new Plugin instance
     *
     * @param Container $app
     * @param $name
     * @param $path
     * @return \Maneash\Plugins\Plugin
     */
    abstract protected function createPlugin(...$args);

    /**
     * Get & scan all plugins.
     *
     * @return array
     */
    public function scan()
    {
        $paths = $this->getScanPaths();

        $plugins = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->app['files']->glob("{$path}/plugin.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('name');

                $plugins[$name] = $this->createPlugin($this->app, $name, dirname($manifest));
            }
        }

        return $plugins;
    }

    /**
     * Get all plugins.
     *
     * @return array
     */
    public function all() : array
    {
        if (!$this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    /**
     * Format the cached data as array of plugins.
     *
     * @param array $cached
     *
     * @return array
     */
    protected function formatCached($cached)
    {
        $plugins = [];

        foreach ($cached as $name => $plugin) {
            $path = $plugin["path"];

            $plugins[$name] = $this->createPlugin($this->app, $name, $path);
        }

        return $plugins;
    }

    /**
     * Get cached plugins.
     *
     * @return array
     */
    public function getCached()
    {
        return $this->app['cache']->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }

    /**
     * Get all plugins as collection instance.
     *
     * @return Collection
     */
    public function toCollection() : Collection
    {
        return new Collection($this->scan());
    }

    /**
     * Get plugins by status.
     *
     * @param $status
     *
     * @return array
     */
    public function getByStatus($status) : array
    {
        $plugins = [];

        foreach ($this->all() as $name => $plugin) {
            if ($plugin->isStatus($status)) {
                $plugins[$name] = $plugin;
            }
        }

        return $plugins;
    }

    /**
     * Determine whether the given plugin exist.
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name) : bool
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Get list of enabled plugins.
     *
     * @return array
     */
    public function allEnabled() : array
    {
        return $this->getByStatus(1);
    }

    /**
     * Get list of disabled plugins.
     *
     * @return array
     */
    public function allDisabled() : array
    {
        return $this->getByStatus(0);
    }

    /**
     * Get count from all plugins.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->all());
    }

    /**
     * Get all ordered plugins.
     *
     * @param string $direction
     *
     * @return array
     */
    public function getOrdered($direction = 'asc') : array
    {
        $plugins = $this->allEnabled();

        uasort($plugins, function (Plugin $a, Plugin $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }

            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }

            return $a->order > $b->order ? 1 : -1;
        });

        return $plugins;
    }

    /**
     * Get a plugin path.
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path ?: $this->config('paths.plugins', base_path('Plugins'));
    }

    /**
     * Register the plugins.
     */
    public function register()
    {
        foreach ($this->getOrdered() as $plugin) {
            $plugin->register();
        }
    }

    /**
     * Boot the plugins.
     */
    public function boot()
    {
        foreach ($this->getOrdered() as $plugin) {
            $plugin->boot();
        }
    }

    /**
     * Find a specific plugin.
     * @param $name
     * @return mixed|void
     */
    public function find($name)
    {
        foreach ($this->all() as $plugin) {
            if ($plugin->getLowerName() === strtolower($name)) {
                return $plugin;
            }
        }

        return;
    }

    /**
     * Find a specific plugin by its alias.
     * @param $alias
     * @return mixed|void
     */
    public function findByAlias($alias)
    {
        foreach ($this->all() as $plugin) {
            if ($plugin->getAlias() === $alias) {
                return $plugin;
            }
        }

        return;
    }

    /**
     * Find all plugins that are required by a plugin. If the plugin cannot be found, throw an exception.
     *
     * @param $name
     * @return array
     * @throws PluginNotFoundException
     */
    public function findRequirements($name)
    {
        $requirements = [];

        $plugin = $this->findOrFail($name);

        foreach ($plugin->getRequires() as $requirementName) {
            $requirements[] = $this->findByAlias($requirementName);
        }

        return $requirements;
    }

    /**
     * Find a specific plugin, if there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return Plugin
     *
     * @throws PluginNotFoundException
     */
    public function findOrFail($name)
    {
        $plugin = $this->find($name);

        if ($plugin !== null) {
            return $plugin;
        }

        throw new PluginNotFoundException("Plugin [{$name}] does not exist!");
    }

    /**
     * Get all plugins as laravel collection instance.
     *
     * @param $status
     *
     * @return Collection
     */
    public function collections($status = 1) : Collection
    {
        return new Collection($this->getByStatus($status));
    }

    /**
     * Get plugin path for a specific plugin.
     *
     * @param $plugin
     *
     * @return string
     */
    public function getPluginPath($plugin)
    {
        try {
            return $this->findOrFail($plugin)->getPath() . '/';
        } catch (PluginNotFoundException $e) {
            return $this->getPath() . '/' . Str::studly($plugin) . '/';
        }
    }

    /**
     * Get asset path for a specific plugin.
     *
     * @param $plugin
     *
     * @return string
     */
    public function assetPath($plugin) : string
    {
        return $this->config('paths.assets') . '/' . $plugin;
    }

    /**
     * Get a specific config data from a configuration file.
     *
     * @param $key
     *
     * @param null $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->app['config']->get('plugins.' . $key, $default);
    }

    /**
     * Get storage path for plugin used.
     *
     * @return string
     */
    public function getUsedStoragePath() : string
    {
        $directory = storage_path('app/plugins');
        if ($this->app['files']->exists($directory) === false) {
            $this->app['files']->makeDirectory($directory, 0777, true);
        }

        $path = storage_path('app/plugins/plugins.used');
        if (!$this->app['files']->exists($path)) {
            $this->app['files']->put($path, '');
        }

        return $path;
    }

    /**
     * Set plugin used for cli session.
     *
     * @param $name
     *
     * @throws PluginNotFoundException
     */
    public function setUsed($name)
    {
        $plugin = $this->findOrFail($name);

        $this->app['files']->put($this->getUsedStoragePath(), $plugin);
    }

    /**
     * Forget the plugin used for cli session.
     */
    public function forgetUsed()
    {
        if ($this->app['files']->exists($this->getUsedStoragePath())) {
            $this->app['files']->delete($this->getUsedStoragePath());
        }
    }

    /**
     * Get plugin used for cli session.
     * @return string
     * @throws \Maneash\Plugins\Exceptions\PluginNotFoundException
     */
    public function getUsedNow() : string
    {
        return $this->findOrFail($this->app['files']->get($this->getUsedStoragePath()));
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->app['files'];
    }

    /**
     * Get plugin assets path.
     *
     * @return string
     */
    public function getAssetsPath() : string
    {
        return $this->config('paths.assets');
    }

    /**
     * Get asset url from a specific plugin.
     * @param string $asset
     * @return string
     * @throws InvalidAssetPath
     */
    public function asset($asset) : string
    {
        if (str_contains($asset, ':') === false) {
            throw InvalidAssetPath::missingPluginName($asset);
        }
        list($name, $url) = explode(':', $asset);

        $baseUrl = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $this->getAssetsPath());

        $url = $this->app['url']->asset($baseUrl . "/{$name}/" . $url);

        return str_replace(['http://', 'https://'], '//', $url);
    }

    /**
     * Determine whether the given plugin is activated.
     * @param string $name
     * @return bool
     * @throws PluginNotFoundException
     */
    public function enabled($name) : bool
    {
        return $this->findOrFail($name)->enabled();
    }

    /**
     * Determine whether the given plugin is not activated.
     * @param string $name
     * @return bool
     * @throws PluginNotFoundException
     */
    public function disabled($name) : bool
    {
        return !$this->enabled($name);
    }

    /**
     * Enabling a specific plugin.
     * @param string $name
     * @return void
     * @throws \Maneash\Plugins\Exceptions\PluginNotFoundException
     */
    public function enable($name)
    {
        $this->findOrFail($name)->enable();
    }

    /**
     * Disabling a specific plugin.
     * @param string $name
     * @return void
     * @throws \Maneash\Plugins\Exceptions\PluginNotFoundException
     */
    public function disable($name)
    {
        $this->findOrFail($name)->disable();
    }

    /**
     * Delete a specific plugin.
     * @param string $name
     * @return bool
     * @throws \Maneash\Plugins\Exceptions\PluginNotFoundException
     */
    public function delete($name) : bool
    {
        return $this->findOrFail($name)->delete();
    }

    /**
     * Update dependencies for the specified plugin.
     *
     * @param string $plugin
     */
    public function update($plugin)
    {
        with(new Updater($this))->update($plugin);
    }

    /**
     * Install the specified plugin.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $subtree
     *
     * @return \Symfony\Component\Process\Process
     */
    public function install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
    {
        $installer = new Installer($name, $version, $type, $subtree);

        return $installer->run();
    }

    /**
     * Get stub path.
     *
     * @return string|null
     */
    public function getStubPath()
    {
        if ($this->stubPath !== null) {
            return $this->stubPath;
        }

        if ($this->config('stubs.enabled') === true) {
            return $this->config('stubs.path');
        }

        return $this->stubPath;
    }

    /**
     * Set stub path.
     *
     * @param string $stubPath
     *
     * @return $this
     */
    public function setStubPath($stubPath)
    {
        $this->stubPath = $stubPath;

        return $this;
    }
}
