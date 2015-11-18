<?php

namespace DeveloperNaren\Crud\Console\Commands;


use Illuminate\Console\Command;
use DeveloperNaren\Crud\Writers\Controller;

class CrudCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:whole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Controller, Model, View, Request and Migration at Once';


    protected $crudName;

    protected $fieldsStrig;



    function handle() {


        $entity = $this->ask( "What entity are you trying to create crud for?" );
        $fieldsString = $this->ask( "Fields string: in format <field>:<type> separated by comma(,)" );


        $this->call( 'crud:controller',  [ 'entity' => $entity ]  );
        $this->call( 'crud:model',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );
        $this->call( 'crud:view',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );
        $this->call( 'crud:request',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );
        $this->call( 'crud:migration',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );

    }

}