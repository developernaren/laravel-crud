<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Support\Facades\Config;

/**
 * Class Model
 * @package DeveloperNaren\Crud\Writers
 * Writes Model Class
 * ToDO write relation based on foreign keys
 */
class Model extends Writer {


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
    protected $description = 'Create Model';

    /**
     * ToDo
     * Most of these variables are defined in the parent class
     * I am just writing the comments, I do not want to break anything
     * so it a ToDo :D
     */
    protected $fieldArr;

    protected $content = '';

    protected $table;

    protected $tableContent;

    protected $modelVar;

    protected $addFormContent = '';

    protected $listContent = '';

    protected $fillableArr;


    /**
     * @param $entity
     * @param $fieldsString
     * write the file
     */
    function __construct( $entity, $fieldsString ) {

        //just some setters
        $this->setTableName( $entity );
        $this->setModelName( $entity );
        $this->setNameSpace();
        $this->parseFields( $fieldsString );
        $this->prepareModel();



    }

    /**
     * writes the file
     */
    private function prepareModel() {

        //generates fillable
        $this->renderFillable();
        //needs to come from config file
        $target = 'app/Models/' . $this->modelName . ".php";
        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Model.txt';

        $this->write( $template, get_object_vars( $this ), $target );


    }

    /**
     * The name says it all
     */
    function renderFillable() {

        $fieldArr = array_keys( $this->fieldArr );
        $this->fillableArr = "['". implode( "','", $fieldArr ) ."']";
    }

}