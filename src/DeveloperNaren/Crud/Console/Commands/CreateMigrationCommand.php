<?php

namespace DeveloperNaren\Crud\Console\Commands;


use DeveloperNaren\Crud\Writers\Migration;

class CreateMigrationCommand extends CrudCommand{


    /**
     * @var string
     * What command should look like
     */
    protected $signature = 'crud:migration {entity} {fieldsString}';

    protected $description = 'Create Migration for and entity';

    /**
     * Handles the command
     * calls the migration write class
     */


    function handle() {

        $entity = $this->argument( 'entity' );
        $fieldsString = $this->argument('fieldsString');

        new Migration( $entity, $fieldsString);


    }


}