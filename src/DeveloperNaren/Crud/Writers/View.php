<?php

namespace DeveloperNaren\Crud\Writers;

/**
 * Writes the view file
 * Class View
 * @package DeveloperNaren\Crud\Writers
 *
 * ToDo
 * need to make the template dynamic
 * we also do not have a master layout
 */
class View extends Writer
{
    /**
     * @var content of the list content of list.blade.php
     */
    protected $tableContent;

    /**
     * @var content for the add file
     */
    protected $addFormContent;

    /**
     * @var array fields array, can be removed parent has it. just writing comment :)
     */
    protected $fieldArr;


    /**
     * @param $entity
     * @param $fieldsString
     * write the view file no.. files.. , two files :)
     *
     */
    function __construct( $entity, $fieldsString ) {


        //now this is a tricky one the parseField in the parent just did not work
        //I am not sure why but this code need to be removed
        //Guess this is a ToDo then
        $allFields = explode(',', $fieldsString );
        $allFieldArr = [];

        foreach( $allFields as $field ) {

            $thisFieldArr =  explode(':', $field );
            list($name, $type) = $thisFieldArr;
            $allFieldArr[ trim($name)] = trim( $type );
        }

        //echo json_encode( $allFieldArr );

        $this->fieldArr = $allFieldArr;

        //just some setters
        $this->setModelName( $entity );
        $this->setTableName( $entity );
        $this->setModelVar();

        //we have a table in the list file, write that thing
        $this->renderTableBody();
        $this->renderViewInputs();


    }

    /**
     * write table body
     */
    function renderTableBody() {

        //head
        $content = '<thead>' . PHP_EOL;
        //tr
        $content .= '<tr>' . PHP_EOL;

        //you know the rest just creating a table with head and body

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

        //writing the list file
        //ToDo Needs to come from template file
        $target = 'resources/views/' . str_slug( $this->modelName ) ."/list.blade.php" ;
        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/ListView.txt';
        $contentKeyArr = get_object_vars( $this );
        $this->write( $template, $contentKeyArr , $target );

    }


    function renderViewInputs( ) {

        //input fields
        //ToDo needs to be able to add select, radios and checkbox
        //not sure how to do that

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

    /**
     * Prepare the Input field
     * @param $label
     * @param $name
     *
     */
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

    /**
     * Prepare the text area
     * @param $label
     * @param $name
     */
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

    /**
     * Prepare the radio
     * @param $label
     * @param $name
     */

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