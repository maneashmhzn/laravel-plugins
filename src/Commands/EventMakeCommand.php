<?php

namespace Maneash\Plugins\Commands;

use Maneash\Plugins\Support\Config\GenerateConfigReader;
use Maneash\Plugins\Support\Stub;
use Maneash\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class EventMakeCommand extends GeneratorCommand
{
    use PluginCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class for the specified plugin';

    public function getTemplateContents()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->getPluginName());

        return (new Stub('/event.stub', [
            'NAMESPACE' => $this->getClassNamespace($plugin),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    public function getDestinationFilePath()
    {
        $path       = $this->laravel['plugins']->getPluginPath($this->getPluginName());

        $eventPath = GenerateConfigReader::read('event');

        return $path . $eventPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return studly_case($this->argument('name'));
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['plugins']->config('paths.generator.event.path', 'Events');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the event.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }
}
