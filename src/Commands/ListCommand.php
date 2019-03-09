<?php

namespace Demo\Plugins\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of all plugins.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->table(['Name', 'Status', 'Order', 'Path'], $this->getRows());
    }

    /**
     * Get table rows.
     *
     * @return array
     */
    public function getRows()
    {
        $rows = [];

        foreach ($this->getPlugins() as $plugin) {
            $rows[] = [
                $plugin->getName(),
                $plugin->enabled() ? 'Enabled' : 'Disabled',
                $plugin->get('order'),
                $plugin->getPath(),
            ];
        }

        return $rows;
    }

    public function getPlugins()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return $this->laravel['plugins']->getByStatus(1);
                break;

            case 'disabled':
                return $this->laravel['plugins']->getByStatus(0);
                break;

            case 'ordered':
                return $this->laravel['plugins']->getOrdered($this->option('direction'));
                break;

            default:
                return $this->laravel['plugins']->all();
                break;
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only', null, InputOption::VALUE_OPTIONAL, 'Types of plugins will be displayed.', null],
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
        ];
    }
}
