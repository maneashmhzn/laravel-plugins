<?php

namespace Demo\Plugins\Commands;

use Illuminate\Console\Command;
use Demo\Plugins\Migrations\Migrator;
use Demo\Plugins\Traits\MigrationLoaderTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateResetCommand extends Command
{
    use MigrationLoaderTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the plugins migrations.';

    /**
     * @var \Demo\Plugins\Contracts\RepositoryInterface
     */
    protected $plugin;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->plugin = $this->laravel['plugins'];

        $name = $this->argument('plugin');

        if (!empty($name)) {
            $this->reset($name);

            return;
        }

        foreach ($this->plugin->getOrdered($this->option('direction')) as $plugin) {
            $this->line('Running for plugin: <info>' . $plugin->getName() . '</info>');

            $this->reset($plugin);
        }
    }

    /**
     * Rollback migration from the specified plugin.
     *
     * @param $plugin
     */
    public function reset($plugin)
    {
        if (is_string($plugin)) {
            $plugin = $this->plugin->findOrFail($plugin);
        }

        $migrator = new Migrator($plugin);

        $database = $this->option('database');

        if (!empty($database)) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->reset();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
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
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'desc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
        ];
    }
}
