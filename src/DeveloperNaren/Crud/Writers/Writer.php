<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Writer
{
    protected $crudName;

    protected $crudSlug;

    protected $fieldArr;

    protected $tableName;

    protected $modelName;

    protected $modelWNameSpace;

    protected $formRequestWNameSpace;

    protected $baseController;

    protected $modelVar;

    protected $namespace;

    protected $modelVarPlural;

     function parseFields( $fields ) {

        $allFields = explode(',', $fields );

        $allFieldArr = [];

        foreach( $allFields as $field ) {

            $thisFieldArr =  explode(':', $field );
            list($name, $type) = $thisFieldArr;
            $allFieldArr[ trim($name)] = trim( $type );
        }

        $this->fieldArr = $allFieldArr;

    }


    function setTableName( $entity ) {

        $this->tableName = str_plural( snake_case( camel_case( $entity )) );
    }

    function write( $file, $contentKeyArr, $target ) {


        $controllerFile = base_path( $file );

        $content = file_get_contents( $controllerFile );

        $newContent = $content;

        foreach( $contentKeyArr as $key => $value ) {

            if ( !is_array( $value ) && !is_object( $value )) {
                $newContent = str_replace( '%%'. studly_case( $key ) . "%%", $value, $newContent );
            }

        }

        $newDir = '';

        $filePath = explode( '/', $target );

        array_pop( $filePath );


        foreach( $filePath as $t ) {

            if ( empty( $newDir )) {
                $newDir = $t;
            } else {
                $newDir .= "/" . $t;
            }
            if ( !is_dir( $newDir ) )  {
                mkdir( $newDir );
            }

        }

        $file = fopen( base_path ( $target ),"wb");
        fwrite($file,$newContent);
        fclose($file);

    }

    function setBaseController() {

        $this->baseController = Config::get( 'crud.base_controller' );

    }




    /**
     * @param mixed $crudSlug
     */
    public function setCrudSlug($crudSlug) {

        $this->crudSlug = $crudSlug;
    }


    public function setNameSpace() {

        $this->namespace = Config::get('crud.namespace_root');
    }


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



}