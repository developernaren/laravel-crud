<?php

namespace DeveloperNaren\Crud\Writers;


class View extends Writer
{
    protected $tableContent;

    protected $addFormContent;

    protected $fieldArr;



    function __construct( $entity, $fieldsString ) {



        $allFields = explode(',', $fieldsString );
        $allFieldArr = [];

        foreach( $allFields as $field ) {

            $thisFieldArr =  explode(':', $field );
            list($name, $type) = $thisFieldArr;
            $allFieldArr[ trim($name)] = trim( $type );
        }

        //echo json_encode( $allFieldArr );

        $this->fieldArr = $allFieldArr;

        $this->setModelName( $entity );
        $this->setTableName( $entity );
        $this->setModelVar();

        $this->renderTableBody();
        $this->renderViewInputs();


    }

    function renderTableBody() {


        $content = '<thead>' . PHP_EOL;

        $content .= '<tr>' . PHP_EOL;

        foreach( $this->fieldArr as $fieldName =>  $value ) {
            $content .= '<th> '. $fieldName .'</th>' .PHP_EOL;
        }

        $content .= '</tr>' . PHP_EOL;

        $content .= "<thead>" . PHP_EOL;

        $content .= "<tbody>" . PHP_EOL;

        $content .= '@foreach( '. str_plural( $this->modelVar )  .' as '. $this->modelVar.')' . PHP_EOL;

        foreach( $this->fieldArr as $fieldName =>  $value ) {

            $content .= '<td>{!! '.$this->modelVar.'->'. $fieldName .' !!}</td>' . PHP_EOL;
        }

        $content .= '@endforeach' . PHP_EOL;

        $content .= "</tbody>" . PHP_EOL;

        $this->tableContent = $content;

        $target = 'resources/views/' . str_slug( $this->modelName ) ."/list.blade.php" ;
        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/ListView.txt';
        $contentKeyArr = get_object_vars( $this );
        $this->write( $template, $contentKeyArr , $target );

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

        $target = 'resources/views/' . str_slug( $this->modelName ) ."/create.blade.php" ;
        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/CreateView.txt';
        $contentKeyArr = get_object_vars( $this );
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