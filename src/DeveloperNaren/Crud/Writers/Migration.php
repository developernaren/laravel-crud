<?php

namespace DeveloperNaren\Crud\Writers;

class Migration extends Writer{

    protected $migrationContent;




    function __construct( $entity, $fieldString ) {

        $this->setTableName( $entity );
        $this->setModelName( $entity);
        $this->parseFields( $fieldString );
        $this->makeMigration();

    }



    private function makeMigration() {


        foreach( $this->fieldArr as $fieldName => $type ) {

            $this->writeFields( $type, $fieldName );

        }

        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Migration.txt';
        $target = 'database/migrations/'. date( "Y_m_d_") . time() . '_create_' .$this->tableName . '_table.php';

        $contentArr = get_object_vars( $this );
        $this->write( $template, $contentArr, $target );


    }

    private function writeFields( $type, $fieldName ) {



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


        $this->migrationContent .= ' $this->' . $type . "( '". $fieldName ."' );" . PHP_EOL;

    }


}