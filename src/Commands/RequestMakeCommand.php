<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Support\Str;
use Maneash\Plugins\Support\Config\GenerateConfigReader;
use Maneash\Plugins\Support\Stub;
use Maneash\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
{
    use PluginCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified plugin.';

    public function getDefaultNamespace() : string
    {
        return $this->laravel['plugins']->config('paths.generator.request.path', 'Http/Requests');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->getPluginName());

        return (new Stub('/request.stub', [
            'NAMESPACE' => $this->getClassNamespace($plugin),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getPluginPath($this->getPluginName());

        $requestPath = GenerateConfigReader::read('request');

        return $path . $requestPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
