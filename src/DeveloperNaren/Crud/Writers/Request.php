<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Support\Facades\Config;

class Request extends Writer{

    protected $signature = 'crud:request {modelName} {fieldsString}';

    protected $description;

    private $rootNamespace;

    private $modelName;

    private $rules = 'return [';



    function handle() {


        $this->modelName = studly_case( $this->argument( 'modelName' ) ) ;
        $this->parseFields( $this->argument( 'fieldsString' ));
        $this->prepareRules();
        $this->rootNamespace = Config::get( 'crud.namespace_root' );

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