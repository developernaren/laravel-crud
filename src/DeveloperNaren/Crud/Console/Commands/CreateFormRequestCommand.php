<?php

namespace DeveloperNaren\Crud\Console\Commands;


class CreateFormRequestCommand extends CrudCommand
{



    protected $signature = 'crud:request {entity} {fieldsString}';

    protected $description = 'Create request class for an entity';


    function handle() {

        $entity = $this->argument( 'entity' );
        $fieldsString = $this->argument( 'fieldsString' );

        new Model( $entity, $fieldsString );

    }

}