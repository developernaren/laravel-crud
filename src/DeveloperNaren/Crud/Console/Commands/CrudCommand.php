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
    protected $signature = 'crud:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Controller';

    /**
     * @var table that the crud is supposed to generate
     */
    protected $table;

    /**
     * @var array fields of the table
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $formFields = [];

    protected $viewTypes = 'all';

    protected $controller;



    function prepare() {

        $this->prepareModel();
        $this->prepareController();
        $this->prepareView();

    }


    private function parseFields( $fields ) {

        $allFields = explode(',', $fields );

        $allFieldArr = [];

        foreach( $allFields as $field ) {
            list($name, $type) = explode(':', $field );
            $allFieldArr[ trim($name)] = trim( $type );

        }

        $this->fields = $allFieldArr;

    }

    private function prepareModel() {

        $this->table = $this->ask( "What table should I work on");
        $fields = $this->ask( "What are the fields in the table? Please specify in format <fieldname>:<type> separated by comma (,)");
        $fieldArr = $this->parseFields( $fields );


    }

    private function prepareController() {

        $this->controller = $this->ask( "Controller");

    }

    private function prepareView() {

        $viewTypes = $this->ask( "What views should I generate? write all for all");

        $types = str_contains( $viewTypes, ',') ? explode(',', $viewTypes) : ['all'];






    }


    private function generate() {

    }



    function handle() {

        $this->prepareController();
        new Controller( $this->controller );

    }

}