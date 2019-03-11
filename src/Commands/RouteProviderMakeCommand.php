<?php

namespace Maneash\Plugins\Commands;

use Maneash\Plugins\Support\Config\GenerateConfigReader;
use Maneash\Plugins\Support\Stub;
use Maneash\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RouteProviderMakeCommand extends GeneratorCommand
{
    use PluginCommandTrait;

    protected $argumentName = 'plugin';

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'plugin:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Create a new route service provider for the specified plugin.';

    /**
     * The command arguments.
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
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->getPluginName());

        return (new Stub('/route-provider.stub', [
            'NAMESPACE'        => $this->getClassNamespace($plugin),
            'CLASS'            => $this->getFileName(),
            'PLUGIN_NAMESPACE' => $this->laravel['plugins']->config('namespace'),
            'PLUGIN'           => $this->getPluginName(),
            'WEB_ROUTES_PATH'  => $this->getWebRoutesPath(),
            'API_ROUTES_PATH'  => $this->getApiRoutesPath(),
            'LOWER_NAME'       => $plugin->getLowerName(),
        ]))->render();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return 'RouteServiceProvider';
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getPluginPath($this->getPluginName());

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return mixed
     */
    protected function getWebRoutesPath()
    {
        return '/' . $this->laravel['config']->get('stubs.files.routes', 'Routes/web.php');
    }

    /**
     * @return mixed
     */
    protected function getApiRoutesPath()
    {
        return '/' . $this->laravel['config']->get('stubs.files.routes', 'Routes/api.php');
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['plugins']->config('paths.generator.provider.path', 'Providers');
    }
}
