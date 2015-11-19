<?php
/**
 * Created by PhpStorm.
 * User: narendra
 * Date: 11/15/15
 * Time: 5:53 PM
 */

namespace DeveloperNaren\Crud\Console\Commands;


use DeveloperNaren\Crud\Writers\View;
use Illuminate\Console\Command;

class CreateViewCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:view {entity} {fieldsString}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create View';

    /**
     * handles the commands
     * calls the view write command
     */
    function handle() {

        $entity = $this->argument( 'entity' );
        $fieldsString = $this->argument( 'fieldsString' );

        new View( $entity, $fieldsString );

    }

}