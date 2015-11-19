<?php

namespace DeveloperNaren\Crud\Console\Commands;

use DeveloperNaren\Crud\Writers\Request;

class CreateFormRequestCommand extends CrudCommand
{
    /**
     * @var string
     * What command should look like
     */
    protected $signature = 'crud:request {entity} {fieldsString}';

    /**
     * @var string
     * description of the command
     */
    protected $description = 'Create request class for an entity';


    function handle() {

        $entity = $this->argument( 'entity' );
        $fieldsString = $this->argument( 'fieldsString' );

        new Request( $entity, $fieldsString );

    }

}