<?php

namespace Maneash\Plugins\Process;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Maneash\Plugins\Contracts\RepositoryInterface;
use Symfony\Component\Process\Process;

class Installer
{
    /**
     * The plugin name.
     *
     * @var string
     */
    protected $name;

    /**
     * The version of plugin being installed.
     *
     * @var string
     */
    protected $version;

    /**
     * The plugin repository instance.
     * @var \Maneash\Plugins\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * The console command instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The destionation path.
     *
     * @var string
     */
    protected $path;

    /**
     * The process timeout.
     *
     * @var int
     */
    protected $timeout = 3360;
    /**
     * @var null|string
     */
    private $type;
    /**
     * @var bool
     */
    private $tree;

    /**
     * The constructor.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $tree
     */
    public function __construct($name, $version = null, $type = null, $tree = false)
    {
        $this->name = $name;
        $this->version = $version;
        $this->type = $type;
        $this->tree = $tree;
    }

    /**
     * Set destination path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the plugin repository instance.
     * @param \Maneash\Plugins\Contracts\RepositoryInterface $repository
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Set console command instance.
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
     * Set process timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Run the installation process.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function run()
    {
        $process = $this->getProcess();

        $process->setTimeout($this->timeout);

        if ($this->console instanceof Command) {
            $process->run(function ($type, $line) {
                $this->console->line($line);
            });
        }

        return $process;
    }

    /**
     * Get process instance.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function getProcess()
    {
        if ($this->type) {
            if ($this->tree) {
                return $this->installViaSubtree();
            }

            return $this->installViaGit();
        }

        return $this->installViaComposer();
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        if ($this->path) {
            return $this->path;
        }

        return $this->repository->getPluginPath($this->getPluginName());
    }

    /**
     * Get git repo url.
     *
     * @return string|null
     */
    public function getRepoUrl()
    {
        switch ($this->type) {
            case 'github':
                return "git@github.com:{$this->name}.git";

            case 'github-https':
                return "https://github.com/{$this->name}.git";

            case 'gitlab':
                return "git@gitlab.com:{$this->name}.git";
                break;

            case 'bitbucket':
                return "git@bitbucket.org:{$this->name}.git";

            default:

                // Check of type 'scheme://host/path'
                if (filter_var($this->type, FILTER_VALIDATE_URL)) {
                    return $this->type;
                }

                // Check of type 'user@host'
                if (filter_var($this->type, FILTER_VALIDATE_EMAIL)) {
                    return "{$this->type}:{$this->name}.git";
                }

                return;
                break;
        }
    }

    /**
     * Get branch name.
     *
     * @return string
     */
    public function getBranch()
    {
        return is_null($this->version) ? 'master' : $this->version;
    }

    /**
     * Get plugin name.
     *
     * @return string
     */
    public function getPluginName()
    {
        $parts = explode('/', $this->name);

        return Str::studly(end($parts));
    }

    /**
     * Get composer package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        if (is_null($this->version)) {
            return $this->name . ':dev-master';
        }

        return $this->name . ':' . $this->version;
    }

    /**
     * Install the plugin via git.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaGit()
    {
        return new Process(sprintf(
            'cd %s && git clone %s %s && cd %s && git checkout %s',
            base_path(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getDestinationPath(),
            $this->getBranch()
        ));
    }

    /**
     * Install the plugin via git subtree.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaSubtree()
    {
        return new Process(sprintf(
            'cd %s && git remote add %s %s && git subtree add --prefix=%s --squash %s %s',
            base_path(),
            $this->getPluginName(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getPluginName(),
            $this->getBranch()
        ));
    }

    /**
     * Install the plugin via composer.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaComposer()
    {
        return new Process(sprintf(
            'cd %s && composer require %s',
            base_path(),
            $this->getPackageName()
        ));
    }
}
