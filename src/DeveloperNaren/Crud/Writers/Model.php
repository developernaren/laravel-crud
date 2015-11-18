<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Support\Facades\Config;

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

    protected $fieldArr;

    protected $content = '';

    protected $table;

    protected $tableContent;

    protected $modelVar;

    protected $addFormContent = '';

    protected $listContent = '';

    protected $fillableArr;



    function __construct( $entity, $fieldsString ) {


        $this->setTableName( $entity );
        $this->setModelName( $entity );
        $this->setNameSpace();
        $this->parseFields( $fieldsString );
        $this->prepareModel();



    }

    private function prepareModel() {

        $this->renderFillable();
        $target = 'app/Models/' . $this->modelName . ".php";
        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Model.txt';

        $this->write( $template, get_object_vars( $this ), $target );


    }

    function renderFillable() {

        $fieldArr = array_keys( $this->fieldArr );
        $this->fillableArr = "['". implode( "','", $fieldArr ) ."']";
    }

    function handle() {

        $this->prepareModel();

    }







}