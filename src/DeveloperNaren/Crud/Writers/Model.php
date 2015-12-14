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

    private $target;

    private $relation = '';


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
        $this->setTarget();
        $this->parseFields( $fieldsString );
        $this->prepareModel();


    }

    function setTarget() {

        $this->target = Config::get( 'crud.model_target' );
    }

    /**
     * writes the file
     */
    private function prepareModel() {

        //generates fillable
        $this->renderFillable();
        //needs to come from config file
        //added from config file
        $target = $this->target . '/' . $this->modelName . ".php";

        $this->writeRelations();

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

    function writeRelations( ) {


        if ( !empty ( $this->foreignKeyArr ) ) {

            foreach( $this->foreignKeyArr as $fieldName ) {
                list( $frStr, $tableNFk ) = explode( '-', $fieldName );

                list( $tableName, $fkTableField ) = explode( '.', $tableNFk );
                $this->relation .= 'function ' . camel_case( $tableName ) . '() {'. PHP_EOL;
                $this->relation .= '$this->belongsTo( "'. $this->namespace .'\\'. studly_case( str_singular( $tableName ) ) .'" );' .PHP_EOL ;
                $this->relation .= "}" . PHP_EOL;
            }

        }



    }



}