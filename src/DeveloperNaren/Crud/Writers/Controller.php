<?php


namespace DeveloperNaren\Crud\Writers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use File;


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
     * @var type handles the controller type whether it is explicit or implicit
     * @var routeName handles the name for the route to be made along with the controller
     */
    function __construct( $entity,$type,$routeName ) {

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
        $this->setTemplate($type);
        $this->setBaseController();
        $this->setCurrentNameSpace();
       $this->setRouteName($routeName,$type,$entity);


        //asigning variables to replace
        $objectVars = get_object_vars( $this );

        //assigning targer
        $target = 'app/Http/Controllers/' . $this->modelName . 'Controller.php';
        //write the file
        //ToDo the template file and the target should be read from config file
        $this->write( $this->template, $objectVars, $target );

    }

    /**
     * set the template
     */

    private function setTemplate($type) {

        //Oh I did read the template from config ..good
        //but need to be able pass the key of the confib because can be multiple file write in single class call
        $template = Config::get( 'crud.controller_template' );

//        checking if its implicit or explicit controller

        if($type == "i") {
            if (empty($template)) {
                $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/ControllerI.txt';
            }



        } elseif($type == "e") {
            $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/ControllerE.txt';

        }

        $this->template = $template;
    }



    /*
     * Set the RouteName
     */
    private function setRouteName($routeName,$type,$entity) {
//        Path to the routes.php
        $route_path = app_path('Http/routes.php');

//        Open routes.php
        $open_route_path = fopen($route_path,'a');

        if($type == 'i') {
            $append_code = 'Route::controller('."$routeName".','."$entity".');';

        } elseif($type == 'e') {

            $append_code = 'Route::resource('."$routeName".','."$entity".');';
        }

//        writing to the routes.php file

        fwrite($open_route_path,$append_code);
        fclose($open_route_path);



    }



}