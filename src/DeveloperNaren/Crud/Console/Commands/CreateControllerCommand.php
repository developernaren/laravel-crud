<?php
/**
 * Created by PhpStorm.
 * User: narendra
 * Date: 11/16/15
 * Time: 9:19 AM
 */

namespace DeveloperNaren\Crud\Console\Commands;

use DeveloperNaren\Crud\Writers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;


class CreateControllerCommand extends CrudCommand
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:controller {entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Controller';


    /**
     * @param string $controllerContent
     */
    function handle() {

        $this->crudName =$this->argument( "entity");;
        new Controller( $this->crudName );
    }

}