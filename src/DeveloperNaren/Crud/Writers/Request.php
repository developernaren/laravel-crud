<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Support\Facades\Config;

class Request extends Writer{

    protected $modelName;

    /**
     * @var rules for the validation
     * ToDO we should be able to pass validation rules
     * honestly dont know how to do that. Have not really thought about it
     */
    protected $rules = 'return [';


    /**
     * Create that damn file
     * @param $entity
     * @param $fieldsString
     */
    function __construct( $entity, $fieldsString ) {


        //just setters
        //you know what I love saying "mutators" fancy :D
        $this->setNameSpace();
        $this->setModelName( $entity );
        $this->setTableName( $entity );
        $this->parseFields( $fieldsString );

        $this->prepareRules();

        //ToDo
        $target = 'app/Http/Requests/' . studly_case( $this->modelName ) . '.php';
        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Request.txt';
        $this->write( $template, get_object_vars( $this ), $target );


    }

    /**
     * rules for validation
     * read the to-do of the class
     */
    private  function prepareRules () {

        foreach( $this->fieldArr as $fieldName => $type ) {

            $this->rules .=     "'$fieldName' => 'required'," . PHP_EOL;
        }

        $this->rules .= '];';
    }


}