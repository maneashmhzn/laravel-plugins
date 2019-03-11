<?php

namespace Maneash\Plugins\Providers;

use Illuminate\Support\ServiceProvider;
use Maneash\Plugins\Commands\CommandMakeCommand;
use Maneash\Plugins\Commands\ControllerMakeCommand;
use Maneash\Plugins\Commands\DisableCommand;
use Maneash\Plugins\Commands\DumpCommand;
use Maneash\Plugins\Commands\EnableCommand;
use Maneash\Plugins\Commands\EventMakeCommand;
use Maneash\Plugins\Commands\FactoryMakeCommand;
use Maneash\Plugins\Commands\InstallCommand;
use Maneash\Plugins\Commands\JobMakeCommand;
use Maneash\Plugins\Commands\ListCommand;
use Maneash\Plugins\Commands\ListenerMakeCommand;
use Maneash\Plugins\Commands\MailMakeCommand;
use Maneash\Plugins\Commands\MiddlewareMakeCommand;
use Maneash\Plugins\Commands\MigrateCommand;
use Maneash\Plugins\Commands\MigrateRefreshCommand;
use Maneash\Plugins\Commands\MigrateResetCommand;
use Maneash\Plugins\Commands\MigrateRollbackCommand;
use Maneash\Plugins\Commands\MigrateStatusCommand;
use Maneash\Plugins\Commands\MigrationMakeCommand;
use Maneash\Plugins\Commands\ModelMakeCommand;
use Maneash\Plugins\Commands\PluginMakeCommand;
use Maneash\Plugins\Commands\NotificationMakeCommand;
use Maneash\Plugins\Commands\PolicyMakeCommand;
use Maneash\Plugins\Commands\ProviderMakeCommand;
use Maneash\Plugins\Commands\PublishCommand;
use Maneash\Plugins\Commands\PublishConfigurationCommand;
use Maneash\Plugins\Commands\PublishMigrationCommand;
use Maneash\Plugins\Commands\PublishTranslationCommand;
use Maneash\Plugins\Commands\RequestMakeCommand;
use Maneash\Plugins\Commands\ResourceMakeCommand;
use Maneash\Plugins\Commands\RouteProviderMakeCommand;
use Maneash\Plugins\Commands\RuleMakeCommand;
use Maneash\Plugins\Commands\SeedCommand;
use Maneash\Plugins\Commands\SeedMakeCommand;
use Maneash\Plugins\Commands\SetupCommand;
use Maneash\Plugins\Commands\TestMakeCommand;
use Maneash\Plugins\Commands\UnUseCommand;
use Maneash\Plugins\Commands\UpdateCommand;
use Maneash\Plugins\Commands\UseCommand;

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
