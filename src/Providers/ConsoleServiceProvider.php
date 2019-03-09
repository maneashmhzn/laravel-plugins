<?php

namespace Demo\Plugins\Providers;

use Illuminate\Support\ServiceProvider;
use Demo\Plugins\Commands\CommandMakeCommand;
use Demo\Plugins\Commands\ControllerMakeCommand;
use Demo\Plugins\Commands\DisableCommand;
use Demo\Plugins\Commands\DumpCommand;
use Demo\Plugins\Commands\EnableCommand;
use Demo\Plugins\Commands\EventMakeCommand;
use Demo\Plugins\Commands\FactoryMakeCommand;
use Demo\Plugins\Commands\InstallCommand;
use Demo\Plugins\Commands\JobMakeCommand;
use Demo\Plugins\Commands\ListCommand;
use Demo\Plugins\Commands\ListenerMakeCommand;
use Demo\Plugins\Commands\MailMakeCommand;
use Demo\Plugins\Commands\MiddlewareMakeCommand;
use Demo\Plugins\Commands\MigrateCommand;
use Demo\Plugins\Commands\MigrateRefreshCommand;
use Demo\Plugins\Commands\MigrateResetCommand;
use Demo\Plugins\Commands\MigrateRollbackCommand;
use Demo\Plugins\Commands\MigrateStatusCommand;
use Demo\Plugins\Commands\MigrationMakeCommand;
use Demo\Plugins\Commands\ModelMakeCommand;
use Demo\Plugins\Commands\PluginMakeCommand;
use Demo\Plugins\Commands\NotificationMakeCommand;
use Demo\Plugins\Commands\PolicyMakeCommand;
use Demo\Plugins\Commands\ProviderMakeCommand;
use Demo\Plugins\Commands\PublishCommand;
use Demo\Plugins\Commands\PublishConfigurationCommand;
use Demo\Plugins\Commands\PublishMigrationCommand;
use Demo\Plugins\Commands\PublishTranslationCommand;
use Demo\Plugins\Commands\RequestMakeCommand;
use Demo\Plugins\Commands\ResourceMakeCommand;
use Demo\Plugins\Commands\RouteProviderMakeCommand;
use Demo\Plugins\Commands\RuleMakeCommand;
use Demo\Plugins\Commands\SeedCommand;
use Demo\Plugins\Commands\SeedMakeCommand;
use Demo\Plugins\Commands\SetupCommand;
use Demo\Plugins\Commands\TestMakeCommand;
use Demo\Plugins\Commands\UnUseCommand;
use Demo\Plugins\Commands\UpdateCommand;
use Demo\Plugins\Commands\UseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        DisableCommand::class,
        DumpCommand::class,
        EnableCommand::class,
        EventMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        MailMakeCommand::class,
        MiddlewareMakeCommand::class,
        NotificationMakeCommand::class,
        ProviderMakeCommand::class,
        RouteProviderMakeCommand::class,
        InstallCommand::class,
        ListCommand::class,
        PluginMakeCommand::class,
        FactoryMakeCommand::class,
        PolicyMakeCommand::class,
        RequestMakeCommand::class,
        RuleMakeCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrateStatusCommand::class,
        MigrationMakeCommand::class,
        ModelMakeCommand::class,
        PublishCommand::class,
        PublishConfigurationCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        UnUseCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        ResourceMakeCommand::class,
        TestMakeCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
