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


    function handle() {

        $this->renderContent( 'table' );

        $this->info( $this->tableContent );


    }



    function renderContent( $type ) {


        switch( $type ) {
            case "table";
                $this->renderTableBody();
        }


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

            $content .= '<td> $'. $value .'</td>';
        }

        $content .= '@endforeach';

        $content .= "</tbody>";

        $this->tableContent = $content;



    }



    function writeView() {


        $contentArr = [ 'TableContent' => $this->tableContent ];






    }










}