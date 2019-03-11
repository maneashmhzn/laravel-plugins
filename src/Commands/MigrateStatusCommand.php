<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Console\Command;
use Maneash\Plugins\Migrations\Migrator;
use Maneash\Plugins\Plugin;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateStatusCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Status for all plugin migrations';

    /**
     * @var \Maneash\Plugins\Contracts\RepositoryInterface
     */
    protected $plugin;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->plugin = $this->laravel['plugins'];

        $name = $this->argument('plugin');

        if ($name) {
            $plugin = $this->plugin->findOrFail($name);

            return $this->migrateStatus($plugin);
        }

        foreach ($this->plugin->getOrdered($this->option('direction')) as $plugin) {
            $this->line('Running for plugin: <info>' . $plugin->getName() . '</info>');
            $this->migrateStatus($plugin);
        }
    }

    /**
     * Run the migration from the specified plugin.
     *
     * @param Plugin $plugin
     */
    protected function migrateStatus(Plugin $plugin)
    {
        $path = str_replace(base_path(), '', (new Migrator($plugin))->getPath());

        $this->call('migrate:status', [
            '--path' => $path,
            '--database' => $this->option('database'),
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
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
        ];
    }
}
