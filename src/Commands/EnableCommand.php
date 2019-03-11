<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class EnableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified plugin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->argument('plugin'));

        if ($plugin->disabled()) {
            $plugin->enable();

            $this->info("Plugin [{$plugin}] enabled successful.");
        } else {
            $this->comment("Plugin [{$plugin}] has already enabled.");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['plugin', InputArgument::REQUIRED, 'Plugin name.'],
        ];
    }
}
