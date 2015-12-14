<?php


namespace DeveloperNaren\Crud\Writers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;


/**
 * Class Controller
 * @package DeveloperNaren\Crud\Writers
 * Writes Controller basically
 * ToDo write route files
 */
class Controller extends Writer
{
    /**
     * @var formRequest Class
     */
    protected $formRequest;

    /**
     * @var route where form is submitted to save
     */
    protected $storeRoute;

    /**
     * @var route where entities are listed
     */
    protected $listRoute;



    /**
     * @param string $controllerContent
     */
    function __construct( $entity ) {

        //everything here is a setter so you know what's up.

        $this->crudName = $entity;

        $this->setCrudSlug( Str::slug( $this->crudName) );
        $this->setNameSpace();
        $this->setModelName( $entity );
        $this->setModelVar( $entity );
        $this->setModelVarPlural();
        $this->setFormRequests();
        $this->setStoreRoute();
        $this->setListRoute();
        $this->setTemplate();
        $this->setBaseController();

        //asigning variables to replace
        $objectVars = get_object_vars( $this );

        //assigning targer
        $target = 'app/Http/Controllers/' . $this->modelName . "Controller.php";
        //write the file
        //ToDo the template file and the target should be read from config file
        $this->write( $this->template, $objectVars, $target );

    }

    /**
     * set the template
     */

    private function setTemplate() {

        //Oh I did read the template from config ..good
        //but need to be able pass the key of the confib because can be multiple file write in single class call
        $template = Config::get( 'crud.controller_template' );

        if ( empty( $template ) ){
            $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Controller.txt';
        }

        $this->template = $template;
    }






}