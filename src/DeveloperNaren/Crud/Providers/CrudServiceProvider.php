<?php
/**
 * Created by PhpStorm.
 * User: narendra
 * Date: 11/15/15
 * Time: 5:32 PM
 */

namespace DeveloperNaren\Crud\Providers;


use DeveloperNaren\Crud\Console\Commands\CreateControllerCommand;
use DeveloperNaren\Crud\Console\Commands\CrudCommand;
use DeveloperNaren\Crud\Writers\Controller;
use DeveloperNaren\Crud\Writers\Model;
use DeveloperNaren\Crud\Writers\Request;
use DeveloperNaren\Crud\Writers\View;
use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('command.crud.controller', function() {

            return new Controller();
        });

        $this->commands('command.crud.controller');

        $this->app->singleton('command.crud.model', function() {

            return new Model();
        });

        $this->commands('command.crud.model');

        $this->app->singleton('command.crud.view', function() {

            return new View();
        });

        $this->commands('command.crud.view');


        $this->app->singleton('command.crud.request', function() {

            return new Request();
        });

        $this->commands('command.crud.request');


    }

    function registerCommands() {

    }


}