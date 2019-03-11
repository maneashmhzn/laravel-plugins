<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Console\Command;
use Maneash\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCommand extends Command
{
    use PluginCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified plugin or for all plugins.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('plugin');

        if ($name) {
            $this->updatePlugin($name);

            return;
        }

        /** @var \Maneash\Plugins\Plugin $plugin */
        foreach ($this->laravel['plugins']->getOrdered() as $plugin) {
            $this->updatePlugin($plugin->getName());
        }
    }

    protected function updatePlugin($name)
    {
        $this->line('Running for plugin: <info>' . $name . '</info>');

        $this->laravel['plugins']->update($name);

        $this->info("Plugin [{$name}] updated successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be updated.'],
        ];
    }
}
