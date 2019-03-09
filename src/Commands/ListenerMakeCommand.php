<?php

namespace Demo\Plugins\Commands;

use Demo\Plugins\Plugin;
use Demo\Plugins\Support\Config\GenerateConfigReader;
use Demo\Plugins\Support\Stub;
use Demo\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends GeneratorCommand
{
    use PluginCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class for the specified plugin';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
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
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for.'],
            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued.'],
        ];
    }

    protected function getTemplateContents()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->getPluginName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getNamespace($plugin),
            'EVENTNAME' => $this->getEventName($plugin),
            'SHORTEVENTNAME' => $this->option('event'),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    private function getNamespace($plugin)
    {
        $listenerPath = GenerateConfigReader::read('listener');

        $namespace = str_replace('/', '\\', $listenerPath->getPath());

        return $this->getClassNamespace($plugin) . "\\" . $namespace;
    }

    protected function getEventName(Plugin $plugin)
    {
        $eventPath = GenerateConfigReader::read('event');

        return $this->getClassNamespace($plugin) . "\\" . $eventPath->getPath() . "\\" . $this->option('event');
    }

    protected function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getPluginPath($this->getPluginName());

        $listenerPath = GenerateConfigReader::read('listener');

        return $path . $listenerPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return studly_case($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->option('queued')) {
            if ($this->option('event')) {
                return '/listener-queued.stub';
            }

            return '/listener-queued-duck.stub';
        }

        if ($this->option('event')) {
            return '/listener.stub';
        }

        return '/listener-duck.stub';
    }
}
