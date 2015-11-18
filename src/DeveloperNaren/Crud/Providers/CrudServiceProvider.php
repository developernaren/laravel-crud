<?php
/**
 * Created by PhpStorm.
 * User: narendra
 * Date: 11/15/15
 * Time: 5:32 PM
 */

namespace DeveloperNaren\Crud\Providers;



use DeveloperNaren\Crud\Console\Commands\CreateControllerCommand;
use DeveloperNaren\Crud\Console\Commands\CreateModelCommand;
use DeveloperNaren\Crud\Console\Commands\CreateMigrationCommand;
use DeveloperNaren\Crud\Console\Commands\CreateFormRequestCommand;
use DeveloperNaren\Crud\Console\Commands\CreateViewCommand;
use DeveloperNaren\Crud\Console\Commands\CrudCommand;
use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{

    private  $commandsArr = [
        'command.crud.controller' => CreateControllerCommand::class,
        'command.crud.model' => CreateModelCommand::class,
        'command.crud.view' => CreateViewCommand::class,
        'command.crud.request' => CreateFormRequestCommand::class,
        'comamnd.crud.migration' => CreateMigrationCommand::class,
        'comamnd.crud.whole' => CrudCommand::class
    ];


    public function register()
    {

        foreach( $this->commandsArr as $command => $class ) {

            $this->app->singleton( $command, function() use ( $class )  {

                return new $class;
            });

            $this->commands( $command );

        }
    }



    public function boot()
    {
        $this->publishes([
                             __DIR__.'/../config/crud.php' => config_path('crud.php'),
                         ], 'config');



    }


}