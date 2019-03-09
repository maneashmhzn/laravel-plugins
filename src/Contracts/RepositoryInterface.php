<?php

namespace Demo\Plugins\Contracts;

interface RepositoryInterface
{
    /**
     * Get all plugins.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get cached plugins.
     *
     * @return array
     */
    public function getCached();

    /**
     * Scan & get all available plugins.
     *
     * @return array
     */
    public function scan();

    /**
     * Get plugins as plugins collection instance.
     *
     * @return \Demo\Plugins\Collection
     */
    public function toCollection();

    /**
     * Get scanned paths.
     *
     * @return array
     */
    public function getScanPaths();

    /**
     * Get list of enabled plugins.
     *
     * @return mixed
     */
    public function allEnabled();

    /**
     * Get list of disabled plugins.
     *
     * @return mixed
     */
    public function allDisabled();

    /**
     * Get count from all plugins.
     *
     * @return int
     */
    public function count();

    /**
     * Get all ordered plugins.
     * @param string $direction
     * @return mixed
     */
    public function getOrdered($direction = 'asc');

    /**
     * Get plugins by the given status.
     *
     * @param int $status
     *
     * @return mixed
     */
    public function getByStatus($status);

    /**
     * Find a specific plugin.
     *
     * @param $name
     *
     * @return mixed
     */
    public function find($name);

    /**
     * Find a specific plugin. If there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return mixed
     */
    public function findOrFail($name);

    public function getPluginPath($pluginName);

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles();
}
