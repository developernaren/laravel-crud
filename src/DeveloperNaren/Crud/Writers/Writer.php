<?php

namespace DeveloperNaren\Crud\Writers;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;

class Writer extends Command
{
    protected $crudName;

    protected $crudSlug;

    protected $fieldArr;

     function parseFields( $fields ) {

        $allFields = explode(',', $fields );

        $allFieldArr = [];

        foreach( $allFields as $field ) {
            list($name, $type) = explode(':', $field );
            $allFieldArr[ trim($name)] = trim( $type );

        }

        $this->fieldArr = $allFieldArr;

    }

    function write( $file, $contentKeyArr, $target ) {


        $controllerFile = base_path( $file );

        $this->info( $controllerFile );

        $content = file_get_contents( $controllerFile );

        $newContent = $content;

        foreach( $contentKeyArr as $key => $value ) {

            if ( !is_array( $value ) && !is_object( $value )) {
                $newContent = str_replace( '%%'. studly_case( $key ) . "%%", $value, $newContent );
            }

        }

        $newDir = '';

        $filePath = explode( '/', $target );


        array_pop( $filePath );
        $this->info( json_encode( $filePath ) );

        foreach( $filePath as $t ) {

            if ( empty( $newDir )) {
                $newDir = $t;
            } else {
                $newDir .= "/" . $t;
            }
            $this->info( $newDir );

            if ( !is_dir( $newDir ) )  {
                mkdir( $newDir );
            }

        }

        $file = fopen( base_path ( $target ),"wb");
        fwrite($file,$newContent);
        fclose($file);

    }


}