<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Container\Container;

/**
 * Class Writer
 * @package DeveloperNaren\Crud\Writers
 * I cannot get the formatting right, I am not sure how to do that
 */
class Writer
{
    /**
     * @var Entity Name
     * ToDO : correct the name
     */
    protected $crudName;

    /**
     * @var
     * Slug that was supposed to be used to create folders,
     * ToDo : this has not been implemented properly
     */
    protected $crudSlug;

    /**
     * @var array of the fields that the fields are supposed to have
     */
    protected $fieldArr = [];

    /**
     * @var
     * name of the table the write creates
     * currently it is plural of the entity
     *
     */
    protected $tableName;

    /**
     * @var
     * Class of the entity
     * studly case of the entity name
     */
    protected $modelName;

    /*
     * @var
     * model namespace prepended with root namespace\Models
     */
    protected $modelWNameSpace;

    /*
     * model namespace prepended with rootnamespace for request
     */
    protected $formRequestWNameSpace;

    /*
     * base controller, this usually can be anything
     * just the controller that every controller extends
     */
    protected $baseController;

    /**
     * @var variable name for the model
     * camel case of the entity name
     */
    protected $modelVar;

    /*
     * @var
     * root namespace
     */
    protected $namespace;

    /*
     * @var plural variable
     * for loops
     */
    protected $modelVarPlural;

    protected $foreignKeyArr;

    /*
     * Var for current namespace
     *  used on setCrudSlug($arg);  method
     */
    protected $currentAppNamespace;

    /**
     * Parses the human readable field string to the machine readable(lol) array
     * @param $fields
     * sets fields array
     */









    function parseFields( $fields ) {

        $allFields = explode(',', $fields );

        $allFieldArr = [];

        foreach( $allFields as $field ) {
            $thisFieldArr =  explode(':', $field );
            list($name, $type) = $thisFieldArr;

            $fieldDetail = $type;

            if ( str_contains( $type, 'fr-' ) ) {
                $fieldDetail = "int";
                $this->foreignKeyArr[ trim($name) ] = $type;

            }
            $allFieldArr[ trim($name)] = $fieldDetail;

        }

        $this->fieldArr = $allFieldArr;

    }

    /**
     * @param $entity
     * set table name
     */
    function setTableName( $entity ) {

        $this->tableName = str_plural( snake_case( camel_case( $entity )) );
    }

    /**
     * Grabs template of a file, replaces the relevant variables and write to taget file
     * @param $file template file
     * @param $contentKeyArr array of what needs to be replace by what
     * @param $target target file where things are to be written
     *
     * Have not used storage class because storage typically only accesses public files
     * whereas we need to access the application files
     *
     */
    function write( $file, $contentKeyArr, $target ) {

        $newContent = $this->replaceVarNReturnContent( $file, $contentKeyArr );
        $this->writeDirectory( $target );
        $this->openAndWriteActualFile( $newContent, $target );

    }

    private  function openAndWriteActualFile( $newContent, $target ) {

        //open the target file
        $file = fopen( base_path ( $target ),"wb");
        //write the file
        fwrite($file,$newContent);
        //save the thing
        fclose($file);
    }


    function writeDirectory( $target ) {

        //we are not sure if all the path directories,
        //checking and creating
        $newDir = '';

        $filePath = explode( '/', $target );

        //last of the array is a file name not a directory, so remove that
        array_pop( $filePath );

        //start the creation
        foreach( $filePath as $t ) {

            //I am not sure why I did this, just kidding
            //actually there was an error with cannot create mkdir() for empty directoty
            if ( empty( $newDir )) {
                $newDir = $t;
            } else {
                //saving the paths
                $newDir .= "/" . $t;
            }
            //if no directory
            if ( !is_dir( $newDir ) )  {
                //create directory
                mkdir( $newDir );
            }

        }
    }

    function replaceVarNReturnContent( $file, $contentKeyArr ) {
        //template file
        $absolutePath = base_path( $file );
        //content of the template ifile
        $content = file_get_contents( $absolutePath );
        //start the replacing
        $newContent = $content;

        foreach( $contentKeyArr as $key => $value ) {

            if ( !is_array( $value ) && !is_object( $value )) {
                //actually replace
                $newContent = str_replace( '%%'. studly_case( $key ) . "%%", $value, $newContent );
            }

        }

        return $newContent . PHP_EOL;
    }

    /*
     * Read the config file for baseController
     */
    function setBaseController() {

        $this->baseController = Config::get( 'crud.base_controller' );

    }


    /**
     * @param $crudSlug
     * supposed to be folder name
     */
    public function setCrudSlug($crudSlug) {

        $this->crudSlug = $crudSlug;
    }

    /**
     * read that config file. READ IT
     */
    public function setNameSpace() {

        $this->namespace = Config::get('crud.namespace_root');

    }

    //you know the rest..don't act like you don't know it.. Peace


    public function setModelName( $entity ) {

        $this->modelName = Str::studly( snake_case( $entity ) );
        $this->modelWNameSpace = $this->namespace ."\Models\\" . $this->modelName;
    }


    public function setFormRequests() {

        $this->formRequest = $this->modelName . 'FormRequest';
        $this->formRequestWNameSpace = $this->namespace ."\Requests\\" . $this->formRequest;

    }


    public function setModelVar() {
        $this->modelVar = '$' . Str::camel( $this->modelName );
    }


    public function setStoreRoute() {
        $this->storeRoute = 'store' . $this->modelName;
    }


    public function setListRoute() {
        $this->listRoute = 'list' . $this->modelName;
    }

    function setModelVarPlural() {
        $this->modelVarPlural = str_plural( $this->modelVar );
    }

    function setCurrentNameSpace() {
        $this->currentAppNamespace = Container::getInstance()->getNamespace();
    }


}