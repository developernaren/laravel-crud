<?php

namespace DeveloperNaren\Crud\Console\Commands;

/**
 * This class actually routes everything to where everything belongs
 * Nothing really happens here
 * Basically this class calls all the relative commands to create a modules
 *
 */


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

    /**
     * handles the command
     * Calls all the relative command to execute to create package
     * ToDo: we have not written a route file. I was thinking of writing the routes to the default route file
     * instead of creating a whole new folder
     *
     */



    function handle() {


        //thougght it would be more interactive and understandable to ask for parameters as questions

        //asking for entity
        $entity = $this->ask( "What entity are you trying to create crud for?" );
        //asking for the fieldsstring
        $fieldsString = $this->ask( "Fields string: in format <field>:<type> separated by comma(,)" );

        //calling command to create controller
        $this->call( 'crud:controller',  [ 'entity' => $entity ]  );
        //ToDo I think we should create the route in this command

        //Calling command to create model for the given entity
        $this->call( 'crud:model',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );

        //calling command to create view for the given entity
        $this->call( 'crud:view',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );

        //calling command to create request for the given entity
        $this->call( 'crud:request',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );

        //calling command to create migration for the given entity
        $this->call( 'crud:migration',   [ 'entity' => $entity, 'fieldsString' => $fieldsString ]  );

    }

}