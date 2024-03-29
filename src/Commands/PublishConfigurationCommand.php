<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PublishConfigurationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a plugin\'s config files to the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($plugin = $this->argument('plugin')) {
            $this->publishConfiguration($plugin);

            return;
        }

        foreach ($this->laravel['plugins']->allEnabled() as $plugin) {
            $this->publishConfiguration($plugin->getName());
        }
    }

    /**
     * @param string $plugin
     * @return string
     */
    private function getServiceProviderForPlugin($plugin)
    {
        $namespace = $this->laravel['config']->get('plugins.namespace');
        $studlyName = studly_case($plugin);

        return "$namespace\\$studlyName\\Providers\\{$studlyName}ServiceProvider";
    }

    /**
     * @param string $plugin
     */
    private function publishConfiguration($plugin)
    {
        $this->call('vendor:publish', [
            '--provider' => $this->getServiceProviderForPlugin($plugin),
            '--force' => $this->option('force'),
            '--tag' => ['config'],
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin being used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['--force', '-f', InputOption::VALUE_NONE, 'Force the publishing of config files'],
        ];
    }
}
