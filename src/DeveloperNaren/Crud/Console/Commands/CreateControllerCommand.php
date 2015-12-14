<?php

namespace DeveloperNaren\Crud\Console\Commands;

use DeveloperNaren\Crud\Writers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * Class CreateControllerCommand
 * @package DeveloperNaren\Crud\Console\Commands
 * create controller command and fires the writer
 */
class CreateControllerCommand extends CrudCommand
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:controller {entity} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Controller';


    /**
     * handles the command.
     * Basically create a controller for the entity
     * calls the controller write class
     * we only need name of the entity to create a controller
     */
    function handle() {


        $this->crudName =$this->argument( "entity");
        $this->type =$this->argument( "type");

        
        //checking whether to create implicit or explicit controllers


        new Controller( $this->crudName );
    }

}