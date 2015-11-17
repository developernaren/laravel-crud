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

    private $content = '';

    private $table;

    private $tableContent;

    private $modelVar;

    private $addFormContent = '';

    private $listContent = '';

    private $rootNamespace;

    private $fillableArr;

    private $modelName;




    private function prepareModel() {

        $this->table = $this->argument( "entity" );
        $this->modelVar = $this->table;
        $this->modelName = studly_case( $this->table );
        $this->rootNamespace = Config::get( 'crud.namespace_root');
        $fields = $this->argument( "fieldsString" );
        $this->parseFields( $fields );
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

        $this->renderContent( 'table' );

        $this->renderViewInputs();
        $this->renderList();

    }

    function renderList() {

        foreach( $this->fieldArr as $fieldName =>  $type ) {

            switch( trim( $type ) ) {

                case "str":
                case "string":
                    $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;
                case "int":
                case "integer":
                    $this->renderInput( 'integer', $fieldName );
                    break;
                case "txt":
                case "text":
                    $this->renderInput( 'text', $fieldName );
                    break;
                case "bool":
                case "boolean":
                    $this->renderInput( 'boolean', $fieldName );
                    break;
                case "dec":
                case "decimal":
                    $this->renderInput( 'decimal', $fieldName );
                    break;
                case "fl":
                case "float":
                    $this->renderInput( 'float', $fieldName );
                    break;
                case "date":
                    $this->renderInput( 'date', $fieldName );
                    break;
                case "datetime":
                case "dttime":
                    $this->renderInput( 'datetime', $fieldName );
                    break;
                case "time":
                    $this->renderInput( 'time', $fieldName );
                    break;

            }
        }

        $target = 'resources/views/' . $this->modelVar ."/create.blade.php" ;



        $contentKeyArr = [ 'AddFormContent' => $this->addFormContent ];

        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/CreateView.txt';

        $this->write( $template, $contentKeyArr , $target );


    }


    function makeMigration() {


        foreach( $this->fieldArr as $fieldName => $type ) {

            $this->writeFields( $type, $fieldName );
        }

        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Migration.txt';
        $target = 'database/migrations/'. date( "Y_m_d_") . time() . '_create_' .$this->table . '_table.php';
        $this->write( $template,['MigrationContent' => $this->content, 'TableName' => studly_case( str_plural( $this->table ) ) ], $target );




    }

    function writeFields( $type, $fieldName ) {



        switch( trim( $type ) ) {

            case "str":
            case "string":
                $this->writeField( 'string', $fieldName );
                break;
            case "int":
            case "integer":
                $this->writeField( 'integer', $fieldName );
                break;
            case "txt":
            case "text":
            $this->writeField( 'text', $fieldName );
                break;
            case "bool":
            case "boolean":
            $this->writeField( 'boolean', $fieldName );
                break;
            case "dec":
            case "decimal":
            $this->writeField( 'decimal', $fieldName );
                break;
            case "fl":
            case "float":
            $this->writeField( 'float', $fieldName );
                break;
            case "date":
                $this->writeField( 'date', $fieldName );
                break;
            case "datetime":
            case "dttime":
                $this->writeField( 'datetime', $fieldName );
                break;
            case "time":
                $this->writeField( 'time', $fieldName );
                break;




        }


    }

    private function writeField( $type, $fieldName) {


        $this->content .= ' $this->' . $type . "( '". $fieldName ."' );" . PHP_EOL;

    }


    function renderContent() {

        $this->renderTableBody();

    }



    function renderTableBody() {


        $content = '<thead>';

        $content .= '<tr>';

        foreach( $this->fieldArr as $fieldName =>  $value ) {
            $content .= '<th> '. $fieldName .'</th>';
        }

        $content .= '</tr>' . PHP_EOL;

        $content .= "<thead>";

        $content .= "<tbody>";




        $content .= '@foreach( $'. str_plural( $this->modelVar )  .' as $'. $this->modelVar.')';

        foreach( $this->fieldArr as $fieldName =>  $value ) {

            $content .= '<td>{!! $'. $fieldName .' !!}</td>';
        }

        $content .= '@endforeach';

        $content .= "</tbody>";

        $this->tableContent = $content;



    }


    function renderViewInputs( ) {


        foreach( $this->fieldArr as $fieldName =>  $type ) {

            switch( trim( $type ) ) {

                case "str":
                case "string":
                    $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;
                case "int":
                case "integer":
                    $this->renderInput( 'integer', $fieldName );
                    break;
                case "txt":
                case "text":
                    $this->renderTextArea( studly_case( $fieldName ), $fieldName );
                    break;
                case "bool":
                case "boolean":
                    $this->renderRadio( studly_case( $fieldName ), $fieldName );
                    break;
                case "dec":
                case "decimal":
                $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;
                case "fl":
                case "float":
                $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;
                case "date":
                    $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;
                case "datetime":
                case "dttime":
                $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;
                case "time":
                    $this->renderInput( studly_case( $fieldName ), $fieldName) ;
                    break;

            }
        }

        $target = 'resources/views/' . $this->modelVar ."/create.blade.php" ;



        $contentKeyArr = [ 'AddFormContent' => $this->addFormContent ];

        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/CreateView.txt';

        $this->write( $template, $contentKeyArr , $target );

    }


    function renderInput( $label, $name ) {

        $this->addFormContent .= '<div class="form-group">
                            <label class="control-label col-lg-4">'. $label .'</label>
                            <div class="col-lg-8">
                                <input type="text" rows="5" class="form-control"
                                          name="' . $name. '" value="{!! $'. $name .' !!}">
                                <span class="help-inline text-danger">{{ $errors->first("'. $name .'") }}</span>
                            </div>
                        </div>';


    }


    function renderTextArea( $label, $name ) {

        $this->addFormContent .= '<div class="form-group">
                            <label class="control-label col-lg-4">'. $label .'</label>
                            <div class="col-lg-8">
                                <textarea rows="5" class="form-control"
                                          name="' . $name. '">{!! $'. $name .' !!}</textarea>
                                <span class="help-inline text-danger">{{ $errors->first("'. $name .'") }}</span>
                            </div>
                        </div>';


    }


    function renderRadio(  $label, $name ) {

        $this->addFormContent .= '<div class="form-group">
                        <label class="control-label col-lg-4">{!! '. $label .'!!}</label>
                        <div class="col-lg-8">
                          <div class="checkbox">
                            <label>
                              <div class="radio"><span class="checked"><input type="radio" checked="" value="option1" name="'. $name.'" class="uniform"></span></div>Checked radio
                            </label>
                          </div><!-- /.checkbox -->
                          <div class="checkbox">
                            <label>
                              <div class="radio"><span><input type="radio" value="option2" name="'. $name.'" class="uniform"></span></div>Unchecked radio
                            </label>
                          </div><!-- /.checkbox -->

                        </div>
                      </div>';

    }

}