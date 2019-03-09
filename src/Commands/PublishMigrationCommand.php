<?php

namespace Demo\Plugins\Commands;

use Illuminate\Console\Command;
use Demo\Plugins\Migrations\Migrator;
use Demo\Plugins\Publishing\MigrationPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishMigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a plugin's migrations to the application";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($name = $this->argument('plugin')) {
            $plugin = $this->laravel['plugins']->findOrFail($name);

            $this->publish($plugin);

            return;
        }

        foreach ($this->laravel['plugins']->allEnabled() as $plugin) {
            $this->publish($plugin);
        }
    }

    /**
     * Publish migration for the specified plugin.
     *
     * @param \Demo\Plugins\Plugin $plugin
     */
    public function publish($plugin)
    {
        with(new MigrationPublisher(new Migrator($plugin)))
            ->setRepository($this->laravel['plugins'])
            ->setConsole($this)
            ->publish();
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
}
