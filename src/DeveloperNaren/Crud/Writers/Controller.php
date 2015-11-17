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


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Controller';

    private $nameSpace;

    private $modelName;

    private $formRequest;

    private $modelVar;

    private $storeRoute;

    private $listRoute;

    private $controllerContent = '';

    private $modelWNameSpace;

    private $formRequestWNameSpace;

    private $baseController = 'BaseController';

    private $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Controller.txt';



    function __constructor() {


    }

    private function setTemplate() {
        $template = Config::get( 'crud.controller_template' );
    }


    /**
     * @param mixed $crudSlug
     */
    public function setCrudSlug($crudSlug) {


        $this->crudSlug = $crudSlug;
    }


    public function setNameSpace() {

        $this->nameSpace = Config::get('crud.namespace_root');
        $this->info( $this->nameSpace);
    }


    public function setModelName() {

        $this->modelName = Str::studly( $this->crudName );
        $this->modelWNameSpace = $this->nameSpace ."\Models\\" . $this->modelName;
    }


    public function setFormRequests() {

        $this->formRequest = $this->modelName . 'FormRequest';
        $this->formRequestWNameSpace = $this->nameSpace ."\Requests\\" . $this->formRequest;

    }


    public function setModelVar() {
        $this->modelVar = '$' . Str::camel( $this->crudName );
    }


    public function setStoreRoute() {
        $this->storeRoute = 'store' . $this->modelName;
    }


    public function setListRoute() {
        $this->listRoute = 'list' . $this->modelName;
    }



    /**
     * @param string $controllerContent
     */
    function handle() {

        $this->crudName =$this->ask( "crud");;

        $this->setCrudSlug( Str::slug( $this->crudName) );

        $this->setNameSpace();
        $this->setModelName();
        $this->setModelVar();
        $this->setFormRequests();
        $this->setStoreRoute();
        $this->setListRoute();
        $this->setTemplate( );

        $objectVars = get_object_vars( $this );

        $target = 'app/Http/Controllers/' . $this->modelName . "Controller.php";
        $this->write( $this->template, $objectVars, $target );

    }

}