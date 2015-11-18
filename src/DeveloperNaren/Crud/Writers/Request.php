<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Support\Facades\Config;

class Request extends Writer{

    protected $modelName;

    protected $rules = 'return [';



    function __construct( $entity, $fieldsString ) {


        $this->setNameSpace();
        $this->setModelName( $entity );
        $this->setTableName( $entity );
        $this->parseFields( $fieldsString );

        $this->prepareRules();
        $target = 'app/Http/Requests/' . studly_case( $this->modelName ) . '.php';
        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Request.txt';
        $this->write( $template, get_object_vars( $this ), $target );


    }

    private  function prepareRules () {

        foreach( $this->fieldArr as $fieldName => $type ) {

            $this->rules .=     "'$fieldName' => 'required'," . PHP_EOL;
        }

        $this->rules .= '];';
    }


}