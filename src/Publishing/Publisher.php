<?php

namespace Maneash\Plugins\Publishing;

use Illuminate\Console\Command;
use Maneash\Plugins\Contracts\PublisherInterface;
use Maneash\Plugins\Contracts\RepositoryInterface;
use Maneash\Plugins\Plugin;

abstract class Publisher implements PublisherInterface
{
    /**
     * The name of plugin will used.
     *
     * @var string
     */
    protected $plugin;

    /**
     * The plugins repository instance.
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * The laravel console instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The success message will displayed at console.
     *
     * @var string
     */
    protected $success;

    /**
     * The error message will displayed at console.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Determine whether the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = true;

    /**
     * The constructor.
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Show the result message.
     *
     * @return self
     */
    public function showMessage()
    {
        $this->showMessage = true;

        return $this;
    }

    /**
     * Hide the result message.
     *
     * @return self
     */
    public function hideMessage()
    {
        $this->showMessage = false;

        return $this;
    }

    /**
     * Get plugin instance.
     *
     * @return \Maneash\Plugins\Plugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Set plugins repository instance.
     * @param RepositoryInterface $repository
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Get plugins repository instance.
     *
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set console instance.
     *
     * @param \Illuminate\Console\Command $console
     *
     * @return $this
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get console instance.
     *
     * @return \Illuminate\Console\Command
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->repository->getFiles();
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    abstract public function getDestinationPath();

    /**
     * Get source path.
     *
     * @return string
     */
    abstract public function getSourcePath();

    /**
     * Publish something.
     */
    public function publish()
    {
        if (!$this->console instanceof Command) {
            $message = "The 'console' property must instance of \\Illuminate\\Console\\Command.";

            throw new \RuntimeException($message);
        }

        if (!$this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath())) {
            return;
        }

        if (!$this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath())) {
            $this->getFilesystem()->makeDirectory($destinationPath, 0775, true);
        }

        if ($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath)) {
            if ($this->showMessage === true) {
                $this->console->line("<info>Published</info>: {$this->plugin->getStudlyName()}");
            }
        } else {
            $this->console->error($this->error);
        }
    }
}
