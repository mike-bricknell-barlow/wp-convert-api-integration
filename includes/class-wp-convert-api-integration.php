<?php

namespace WPCAI;

use ConvertApi\ConvertApi;

class WPConvertApiIntegration {
    private $handler;
    
    function __construct() {
        $this->set_handler();
        add_filter( 'wp_generate_attachment_metadata', [ $this, 'convert_on_resize' ], 10, 2 );
        add_filter( 'wp_delete_file', [ $this, 'delete_converted' ] );
    }

    public function set_handler() {
        $key = get_option( 'wp-convert-api-integration-api-secret' );
        
        if( ! $key ) {
            return;
        }
        
        $this->handler = ConvertApi::class;
        $response = $this->handler::setApiSecret( $key );
    }

    public function get_handler() {
        return $this->handler;
    }

    protected function get_convert_filetypes() {
        return get_option( 'wp-convert-api-integration-allowed-filetypes' );
    }

    public function delete_converted( $file ) {
        $filetypes = \WPCAI\WPConvertApiIntegrationAdmin::get_filetype_options();

        foreach( $filetypes as $filetype ) {
            if( ! file_exists( $file . '.' . $filetype ) ) {
                // No converted file of this type exists
                continue;
            }

            unlink( $file . '.' . $filetype );
        }
        
        return $file;
    }

    public function convert_on_resize( $metadata, $attachment_id ) {
        $file_path_arr = explode( DIRECTORY_SEPARATOR, $metadata['file'] );
        $file_type = false;
        $convert_filetypes = $this->get_convert_filetypes();

        foreach( $metadata['sizes'] as $size ) {
            $filepath = wp_get_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . $file_path_arr[0] . DIRECTORY_SEPARATOR . $file_path_arr[1] . DIRECTORY_SEPARATOR . $size['file'];
            $file_type = array_pop( explode( '.', $size['file'] ) );

            if( ! $file_type ) {
                continue;
            }
            
            foreach( $convert_filetypes as $new_filetype ) {
                $this->convert_file( [
                    'current_filetype' => $file_type,
                    'new_filetype' => $new_filetype,
                    'filepath' => $filepath,
                ] );
            }
            unset( $new_filetype );
        }

        if( ! $file_type ) {
            return $metadata;
        }

        foreach( $convert_filetypes as $new_filetype ) {
            $this->convert_file( [
                'current_filetype' => $file_type,
                'new_filetype' => $new_filetype,
                'filepath' => wp_get_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . $metadata['file'],
            ] );
        }

        return $metadata;
    }

    /**
     *
     * Converts a file from one filetype to another.
     *
     * @param array $arguments Array containing the necessary configuration
     *      $arguments = [
     *          'current_filetype' => (string) Current filetype extension, e.g. 'jpg'. *Required* 
     *          'new_filetype' => (string) Desired new filetype extension, e.g. 'webp'. *Required* 
     *          'filepath' => (string) Absolute path to the file. *Required*
     *      ]
     * 
     * @return void
     *
     */
    protected function convert_file( $arguments ) {
        $handler = $this->get_handler();
    
        $result = $handler::convert( 
            $arguments['new_filetype'], 
            [
                'File' => $arguments['filepath'],
            ], 
            $arguments['current_filetype']
        );
        
        $result->saveFiles( 
            sprintf( 
                '%s.%s',
                $arguments['filepath'], 
                $arguments['new_filetype']  
            )
        );
    }
}