<?php
/**
 * Created by PhpStorm.
 * User: narendra
 * Date: 11/15/15
 * Time: 5:56 PM
 */

namespace DeveloperNaren\Crud\Console\Commands;


use DeveloperNaren\Crud\Writers\Model;
use Illuminate\Console\Command;

class CreateModelCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:model {entity} {fieldsString}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Model for an entity';

    /**
     * handles the command
     * calls the model write class
     */

    function handle() {

        $entity = $this->argument( 'entity' );
        $fieldsString = $this->argument( 'fieldsString' );

        new Model( $entity, $fieldsString );

    }

}