<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class UseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified plugin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plugin = Str::studly($this->argument('plugin'));

        if (!$this->laravel['plugins']->has($plugin)) {
            $this->error("Plugin [{$plugin}] does not exists.");

            return;
        }

        $this->laravel['plugins']->setUsed($plugin);

        $this->info("Plugin [{$plugin}] used successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['plugin', InputArgument::REQUIRED, 'The name of plugin will be used.'],
        ];
    }
}
