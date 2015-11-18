<?php

namespace DeveloperNaren\Crud\Writers;


class View extends Writer
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create View';

    protected $tableContent;

    protected $addFormContent;




    function handle() {

        $this->renderContent( 'table' );

        $this->info( $this->tableContent );

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

        $template = '/vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/CreateView.txt';

        $contentKeyArr = get_object_vars( $this );
        $this->write( $template, $contentKeyArr , $target );

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


}