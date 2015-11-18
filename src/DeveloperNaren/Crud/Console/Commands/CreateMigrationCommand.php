<?php

namespace DeveloperNaren\Crud\Console\Commands;


use DeveloperNaren\Crud\Writers\Migration;

class CreateMigrationCommand extends CrudCommand{


    protected $signature = 'crud:migration {entity} {fieldsString}';

    protected $description = 'Create Migration for and entity';



    function handle() {

        $entity = $this->argument( 'entity' );
        $fieldsString = $this->argument('fieldsString');

        new Migration( $entity, $fieldsString);


    }


}