<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Console\Command;
use Maneash\Plugins\Plugin;
use Maneash\Plugins\Publishing\LangPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishTranslationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish-translation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a plugin\'s translations to the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($name = $this->argument('plugin')) {
            $this->publish($name);

            return;
        }

        $this->publishAll();
    }

    /**
     * Publish assets from all plugins.
     */
    public function publishAll()
    {
        foreach ($this->laravel['plugins']->allEnabled() as $plugin) {
            $this->publish($plugin);
        }
    }

    /**
     * Publish assets from the specified plugin.
     *
     * @param string $name
     */
    public function publish($name)
    {
        if ($name instanceof Plugin) {
            $plugin = $name;
        } else {
            $plugin = $this->laravel['plugins']->findOrFail($name);
        }

        with(new LangPublisher($plugin))
            ->setRepository($this->laravel['plugins'])
            ->setConsole($this)
            ->publish();

        $this->line("<info>Published</info>: {$plugin->getStudlyName()}");
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
}
