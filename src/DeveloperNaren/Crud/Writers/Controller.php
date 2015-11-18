<?php
/**
 * Created by PhpStorm.
 * User: narendra
 * Date: 11/16/15
 * Time: 9:19 AM
 */

namespace DeveloperNaren\Crud\Writers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;



class Controller extends Writer
{

    protected $formRequest;

    protected $storeRoute;

    protected $listRoute;



    /**
     * @param string $controllerContent
     */
    function __construct( $entity ) {

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

        $objectVars = get_object_vars( $this );
        $target = 'app/Http/Controllers/' . $this->modelName . "Controller.php";
        $this->write( $this->template, $objectVars, $target );

    }

    private function setTemplate() {

        $template = Config::get( 'crud.controller_template' );

        if ( empty( $template ) ){
            $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Controller.txt';
        }

        $this->template = $template;
    }






}