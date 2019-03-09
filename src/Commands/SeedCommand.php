<?php

namespace Demo\Plugins\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Str;
use Demo\Plugins\Contracts\RepositoryInterface;
use Demo\Plugins\Plugin;
use Demo\Plugins\Support\Config\GenerateConfigReader;
use Demo\Plugins\Traits\PluginCommandTrait;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedCommand extends Command
{
    use PluginCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database seeder from the specified plugin or from all plugins.';

    /**
     * Execute the console command.
     * @throws FatalThrowableError
     */
    public function handle()
    {
        try {
            if ($name = $this->argument('plugin')) {
                $name = Str::studly($name);
                $this->pluginSeed($this->getPluginByName($name));
            } else {
                $plugins = $this->getPluginRepository()->getOrdered();
                array_walk($plugins, [$this, 'pluginSeed']);
                $this->info('All plugins seeded.');
            }
        } catch (\Throwable $e) {
            $this->reportException($e);

            $this->renderException($this->getOutput(), $e);

            return 1;
        }
    }

    /**
     * @throws RuntimeException
     * @return RepositoryInterface
     */
    public function getPluginRepository(): RepositoryInterface
    {
        $plugins = $this->laravel['plugins'];
        if (!$plugins instanceof RepositoryInterface) {
            throw new RuntimeException('Plugin repository not found!');
        }

        return $plugins;
    }

    /**
     * @param $name
     *
     * @throws RuntimeException
     *
     * @return Plugin
     */
    public function getPluginByName($name)
    {
        $plugins = $this->getPluginRepository();
        if ($plugins->has($name) === false) {
            throw new RuntimeException("Plugin [$name] does not exists.");
        }

        return $plugins->find($name);
    }

    /**
     * @param Plugin $plugin
     *
     * @return void
     */
    public function pluginSeed(Plugin $plugin)
    {
        $seeders = [];
        $name = $plugin->getName();
        $config = $plugin->get('migration');
        if (is_array($config) && array_key_exists('seeds', $config)) {
            foreach ((array)$config['seeds'] as $class) {
                if (class_exists($class)) {
                    $seeders[] = $class;
                }
            }
        } else {
            $class = $this->getSeederName($name); //legacy support
            if (class_exists($class)) {
                $seeders[] = $class;
            }
        }

        if (count($seeders) > 0) {
            array_walk($seeders, [$this, 'dbSeed']);
            $this->info("Plugin [$name] seeded.");
        }
    }

    /**
     * Seed the specified plugin.
     *
     * @param string $className
     */
    protected function dbSeed($className)
    {
        if ($option = $this->option('class')) {
            $params['--class'] = Str::finish(substr($className, 0, strrpos($className, '\\')), '\\') . $option;
        } else {
            $params = ['--class' => $className];
        }

        if ($option = $this->option('database')) {
            $params['--database'] = $option;
        }

        if ($option = $this->option('force')) {
            $params['--force'] = $option;
        }

        $this->call('db:seed', $params);
    }

    /**
     * Get master database seeder name for the specified plugin.
     *
     * @param string $name
     *
     * @return string
     */
    public function getSeederName($name)
    {
        $name = Str::studly($name);

        $namespace = $this->laravel['plugins']->config('namespace');
        $seederPath = GenerateConfigReader::read('seeder');
        $seederPath = str_replace('/', '\\', $seederPath->getPath());

        return $namespace . '\\' . $name . '\\' . $seederPath . '\\' . $name . 'DatabaseSeeder';
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Throwable  $e
     * @return void
     */
    protected function renderException($output, \Throwable $e)
    {
        $this->laravel[ExceptionHandler::class]->renderForConsole($output, $e);
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param  \Throwable  $e
     * @return void
     */
    protected function reportException(\Throwable $e)
    {
        $this->laravel[ExceptionHandler::class]->report($e);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder.'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }
}
