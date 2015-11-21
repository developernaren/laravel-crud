<?php

namespace DeveloperNaren\Crud\Writers;
use Illuminate\Support\Facades\Config;

/**
 * Class Migration
 * @package DeveloperNaren\Crud\Writers
 * writes migration
 *ToDo Needs to support foreign keys
 * the string, I think, should be in format <field>:fr-<foreigntable>.<foreign field>
 */

class Migration extends Writer{

    protected $migrationContent;

    private $target;

    function __construct( $entity, $fieldString ) {

        $this->setTableName( $entity );
        $this->setModelName( $entity);
        $this->parseFields( $fieldString );
        $this->setTarget();
        $this->makeMigration();

    }

    function setTarget() {

        $this->target = Config::get( 'crud.migration_target' );
    }

    private function makeMigration() {


        foreach( $this->fieldArr as $fieldName => $type ) {
            //write fields for the given array
            $this->writeFields( $type, $fieldName );

        }

        // need to get these files from config
        $template = 'vendor/developernaren/laravel-crud/src/DeveloperNaren/Crud/Templates/Migration.txt';
        $target = $this->target . '/'. date( "Y_m_d_") . time() . '_create_' .$this->tableName . '_table.php';

        $contentArr = get_object_vars( $this );
        $this->write( $template, $contentArr, $target );


    }

    private function writeFields( $type, $fieldName ) {

        /**
         * parse the arrays and write the fields
         */

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

    /**
     * Write the actual migration code
     * @param $type type of the field
     * @param $fieldName name of the field
     * ToDo get support for foreign keys
     *
     */
    private function writeField( $type, $fieldName) {

        if ( !empty ( $this->foreignKeyArr[ $fieldName ] ) ) {

            list( $frStr, $tableNFk ) = explode( '-', $this->foreignKeyArr[ $fieldName ] );

            list( $tableName, $fkTableField ) = explode( '.', $tableNFk );

            $this->migrationContent .= ' $table->index()->unsigned()->integer("'. $fieldName .'")';
                if ( $frStr == 'nlfr-' ) {
                    $this->migrationContent .= '->nullable()';
                }

                $this->migrationContent .= ";" . PHP_EOL;
            $this->migrationContent .= ' $table->foreign("'. $fieldName.'")'
            .'->references("'. $tableName.'")->on("'. $fkTableField .'")->unsigned()->integer("'. $fieldName .'")' . ";". PHP_EOL;
            return;
        }

        $this->migrationContent .= ' $table->' . $type . "( '". $fieldName ."' );" . PHP_EOL;

    }


}