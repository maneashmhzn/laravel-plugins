<?php

namespace Maneash\Plugins\Commands;

use Maneash\Plugins\Support\Config\GenerateConfigReader;
use Maneash\Plugins\Support\Stub;
use Maneash\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

final class NotificationMakeCommand extends GeneratorCommand
{
    use PluginCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-notification';

    protected $argumentName = 'name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class for the specified plugin.';

    public function getDefaultNamespace() : string
    {
        return $this->laravel['plugins']->config('paths.generator.notifications.path', 'Notifications');
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->getPluginName());

        return (new Stub('/notification.stub', [
            'NAMESPACE' => $this->getClassNamespace($plugin),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getPluginPath($this->getPluginName());

        $notificationPath = GenerateConfigReader::read('notifications');

        return $path . $notificationPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return studly_case($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the notification class.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }
}
