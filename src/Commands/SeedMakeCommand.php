<?php

namespace Maneash\Plugins\Commands;

use Illuminate\Support\Str;
use Maneash\Plugins\Support\Config\GenerateConfigReader;
use Maneash\Plugins\Support\Stub;
use Maneash\Plugins\Traits\CanClearPluginsCache;
use Maneash\Plugins\Traits\PluginCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedMakeCommand extends GeneratorCommand
{
    use PluginCommandTrait, CanClearPluginsCache;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new seeder for the specified plugin.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of seeder will be created.'],
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
            [
                'master',
                null,
                InputOption::VALUE_NONE,
                'Indicates the seeder will created is a master database seeder.',
            ],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $plugin = $this->laravel['plugins']->findOrFail($this->getPluginName());

        return (new Stub('/seeder.stub', [
            'NAME' => $this->getSeederName(),
            'PLUGIN' => $this->getPluginName(),
            'NAMESPACE' => $this->getClassNamespace($plugin),

        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $this->clearCache();

        $path = $this->laravel['plugins']->getPluginPath($this->getPluginName());

        $seederPath = GenerateConfigReader::read('seeder');

        return $path . $seederPath->getPath() . '/' . $this->getSeederName() . '.php';
    }

    /**
     * Get seeder name.
     *
     * @return string
     */
    private function getSeederName()
    {
        $end = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        return Str::studly($this->argument('name')) . $end;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return $this->laravel['plugins']->config('paths.generator.seeder.path', 'Database/Seeders');
    }
}
