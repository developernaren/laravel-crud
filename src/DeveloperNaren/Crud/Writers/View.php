<?php

namespace DeveloperNaren\Crud\Writers;
use Illuminate\Support\Facades\Config;

/**
 * Writes the view file
 * Class View
 * @package DeveloperNaren\Crud\Writers
 *
 * ToDo
 * need to make the template dynamic
 * we also do not have a master layout
 */
class View extends Writer {
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

    private $checkBoxTemplate;

    private $checkBoxDivTemplate;

    private $formLayoutTemplate;

    private $textTemplate;

    private $pageLayoutTemplate;

    private $radioTemplate;

    private $radioDivTemplate;

    private $selectTemplate;

    private $textAreaTemplate;

    private $viewTarget;

    private $completeForm;



    /**
     * @param $entity
     * @param $fieldsString
     * write the view file no.. files.. , two files :)
     *
     */
    function __construct($entity, $fieldsString) {

        $this->parseFields( $fieldsString );
        //just some setters
        $this->setModelName($entity);
        $this->setTableName($entity);
        $this->setModelVar();

        //setting templates for everything, I hate doing things like this.
        //ToDo someone create an array loop over the methods please
        $this->setCheckBoxDivTemplate();
        $this->setCheckBoxTemplate();
        $this->setFormLayoutTemplate();
        $this->setPageLayoutTemplate();
        $this->setRadioDivTemplate();
        $this->setRadioTemplate();
        $this->setTextTemplate();
        $this->setTextAreaTemplate();
        $this->setSelectTemplate();
        $this->setViewTarget();

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

        foreach ($this->fieldArr as $fieldName => $value) {
            $content .= '<th> ' . $fieldName . '</th>' . PHP_EOL;
        }

        $content .= '</tr>' . PHP_EOL;

        $content .= "<thead>" . PHP_EOL;

        $content .= "<tbody>" . PHP_EOL;

        $content .= '@foreach( ' . str_plural($this->modelVar) . ' as ' . $this->modelVar . ')' . PHP_EOL;

        foreach ($this->fieldArr as $fieldName => $value) {

            $content .= '<td>{!! ' . $this->modelVar . '->' . $fieldName . ' !!}</td>' . PHP_EOL;
        }

        $content .= '@endforeach' . PHP_EOL;

        $content .= "</tbody>" . PHP_EOL;

        $this->tableContent = $content;

        //writing the list file
        //ToDo Needs to come from template file
        $target = $this->viewTarget . '/' . str_slug($this->modelName) . "/list.blade.php";
        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/ListView.txt';
        $contentKeyArr = get_object_vars($this);
        $this->write($template, $contentKeyArr, $target);

    }


    function renderViewInputs() {

        //input fields
        //ToDo needs to be able to add select, radios and checkbox
        //not sure how to do that

        foreach ($this->fieldArr as $fieldName => $type) {

            if (!empty($this->foreignKeyArr[$fieldName])) {

                $this->renderSelect( $fieldName, $this->foreignKeyArr[$fieldName]);

            } else if( str_contains( $type, 'enumr.')) {

                $this->renderRadio( $fieldName, $type );

            } else if( str_contains( $type, 'enumc.') ) {

                $this->renderCheckBox( $fieldName, $type );
            } else {

                switch (trim($type)) {

                    case "str":
                    case "string":
                        $this->renderText(studly_case($fieldName), $fieldName);
                        break;
                    case "int":
                    case "integer":
                        $this->renderInput('integer', $fieldName);
                        break;
                    case "txt":
                    case "text":
                        $this->renderTextArea(studly_case($fieldName), $fieldName);
                        break;
                    case "bool":
                    case "boolean":
                        $this->renderRadio(studly_case($fieldName), $fieldName);
                        break;
                    case "dec":
                    case "decimal":
                        $this->renderInput(studly_case($fieldName), $fieldName);
                        break;
                    case "fl":
                    case "float":
                        $this->renderInput(studly_case($fieldName), $fieldName);
                        break;
                    case "date":
                        $this->renderInput(studly_case($fieldName), $fieldName);
                        break;
                    case "datetime":
                    case "dttime":
                        $this->renderInput(studly_case($fieldName), $fieldName);
                        break;
                    case "time":
                        $this->renderInput(studly_case($fieldName), $fieldName);
                        break;
                    case "enum":
                        $this->renderRadio(studly_case($fieldName), $fieldName);
                        break;

                }

            }

        }


        $this->renderForm();
        $target = $this->viewTarget . '/' . str_slug($this->modelName) . "/create.blade.php";
        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/CreateView.txt';
        $contentKeyArr = ['form' => $this->completeForm ];
        $this->write($template, $contentKeyArr, $target);

    }

    function renderForm() {

        $contentKeyArr = [
            'addFormAction' => 'save' . $this->modelName,
            'formContent'   => $this->addFormContent
        ];

         $this->completeForm =  $this->replaceVarNReturnContent( $this->formLayoutTemplate, $contentKeyArr );

    }


    /**
     * Prepare the Input field
     * @param $label
     * @param $name
     *
     */
    function renderText($label, $name) {

        $contentKeyArr = [
            'fieldName' => $name,
            'fieldLabel' => $label
        ];

        $this->addFormContent .= $this->replaceVarNReturnContent( $this->textTemplate, $contentKeyArr );

    }


    /**
     * Prepare the text area
     * @param $label
     * @param $name
     */
    function renderTextArea($label, $name) {

        $contentKeyArr = [
            'fieldName' => $name,
            'fieldLabel' => $label
        ];

        $this->addFormContent .= $this->replaceVarNReturnContent( $this->textAreaTemplate, $contentKeyArr );

    }


    /**
     * @param $name name of the input
     * @param $type what are we trying to render
     */

    function renderRadio( $name, $type ) {

        $label = ucwords( str_replace( '_', ' ', snake_case( $name ) ) );

        $radios = '';

        $options = explode('.', $type);

        array_shift( $options );

        foreach( $options as $option ) {

            $contentKeyArr = [
                'fieldName' => snake_case( $option ),
                'value' => $option,
                'fieldLabel' => ucwords( str_replace( '_', ' ', snake_case( $option ) ) )
            ];

            $radios .= $this->replaceVarNReturnContent( $this->radioTemplate, $contentKeyArr );
        }

        $radioDivContentArr = [
            'fieldLabel' => $label,
            'radio' => $radios,

        ];

        $this->addFormContent .= $this->replaceVarNReturnContent( $this->radioDivTemplate, $radioDivContentArr );

    }

    function renderCheckBox( $name, $type ) {

        $label = ucwords( str_replace( '_', ' ', snake_case( $name ) ) );

        $radios = '';

        $options = explode('.', $type);

        array_shift( $options );

        foreach( $options as $option ) {

            $contentKeyArr = [
                'fieldName' => snake_case( $name ),
                'value' => $option,
                'fieldLabel' => ucwords( str_replace( '_', ' ', snake_case( $option ) ) )
            ];

            $radios .= $this->replaceVarNReturnContent( $this->checkBoxTemplate, $contentKeyArr );
        }

        $radioDivContentArr = [
            'fieldLabel' => $label,
            'checkBoxes' => $radios,

        ];

        $this->addFormContent .= $this->replaceVarNReturnContent( $this->checkBoxDivTemplate, $radioDivContentArr );





    }


    function renderSelect($fieldName, $foreignStr) {

        if (!empty ($this->foreignKeyArr)) {


            list($frStr, $tableNFk) = explode('-', $foreignStr);
            list($tableName, $fkTableField) = explode('.', $tableNFk);
            $contentArr = [
                'modelVarPlural' => $tableName,
                'modelVar' => str_singular( $tableName ),
                'FieldLabel' => ucwords( str_replace( '_', ' ', snake_case( str_singular( $tableName ) ) ) )
            ];

            $this->addFormContent .= $this->replaceVarNReturnContent( $this->selectTemplate, $contentArr );

        }

    }


    public function setCheckBoxTemplate() {
        $this->checkBoxTemplate =  Config::get( 'crud.checkbox_template' );
    }


    public function setCheckBoxDivTemplate() {
        $this->checkBoxDivTemplate = Config::get( 'crud.checkbox_div_template' );
    }


    public function setFormLayoutTemplate() {
        $this->formLayoutTemplate = Config::get( 'crud.form_layout_template' );
    }


    public function setTextTemplate() {
        $this->textTemplate = Config::get( 'crud.text_template' );
    }


    public function setPageLayoutTemplate() {
        $this->pageLayoutTemplate = Config::get( 'crud.page_layout_template' );
    }


    public function setRadioTemplate() {
        $this->radioTemplate = Config::get( 'crud.radio_template' );
    }


    public function setRadioDivTemplate() {
        $this->radioDivTemplate  = Config::get( 'crud.radio_div_template' );
    }


    public function setSelectTemplate() {
        $this->selectTemplate = Config::get( 'crud.select_template' );
    }


    public function setTextAreaTemplate() {
        $this->textAreaTemplate  = Config::get( 'crud.textarea_template' );;
    }

    /**
     * @param mixed $viewTarget
     */
    public function setViewTarget() {
        $this->viewTarget = Config::get('crud.view_target');
    }







}