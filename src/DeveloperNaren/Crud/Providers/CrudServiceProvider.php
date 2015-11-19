<?php

namespace DeveloperNaren\Crud\Providers;

/**
 * Registers all the commands
 */


use DeveloperNaren\Crud\Console\Commands\CreateControllerCommand;
use DeveloperNaren\Crud\Console\Commands\CreateModelCommand;
use DeveloperNaren\Crud\Console\Commands\CreateMigrationCommand;
use DeveloperNaren\Crud\Console\Commands\CreateFormRequestCommand;
use DeveloperNaren\Crud\Console\Commands\CreateViewCommand;
use DeveloperNaren\Crud\Console\Commands\CrudCommand;
use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{

    /**
     * @var array
     * All the commands that we have
     */

    private  $commandsArr = [
        'command.crud.controller' => CreateControllerCommand::class,
        'command.crud.model' => CreateModelCommand::class,
        'command.crud.view' => CreateViewCommand::class,
        'command.crud.request' => CreateFormRequestCommand::class,
        'comamnd.crud.migration' => CreateMigrationCommand::class,
        'comamnd.crud.whole' => CrudCommand::class
    ];

    /**
     * registers the commands for use
     */

    public function register()
    {

        foreach( $this->commandsArr as $command => $class ) {

            $this->app->singleton( $command, function() use ( $class )  {

                return new $class;
            });

            $this->commands( $command );

        }
    }

    /**
     * publishes the config file
     */


    public function boot()
    {
        $this->publishes([
                             __DIR__.'/../config/crud.php' => config_path('crud.php'),
                         ], 'config');



    }


}