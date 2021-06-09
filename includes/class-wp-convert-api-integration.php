<?php

namespace WPCAI;

use ConvertApi\ConvertApi;

class WPConvertApiIntegration {
    private $handler;
    
    function __construct() {
        $this->set_handler();
        add_filter( 'wp_generate_attachment_metadata', array( $this, 'generate_webp_on_resize' ), 10, 2 );
    }

    public function set_handler() {
        $key = get_option( 'wp-convert-api-integration-api-secret' );
        
        if( ! $key ) {
            return;
        }
        
        $this->handler = ConvertApi::class;
        $this->handler::setApiSecret( $key );
    }

    public function get_handler() {
        return $this->handler;
    }

    public function generate_webp_on_resize( $metadata, $attachment_id ) {
        $file_path_arr = explode( DIRECTORY_SEPARATOR, $metadata['file'] );
        $mime_type = false;

        foreach( $metadata['sizes'] as $size ) {
            $filepath = wp_get_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . $file_path_arr[0] . DIRECTORY_SEPARATOR . $file_path_arr[1] . DIRECTORY_SEPARATOR . $size['file'];
            $mime_type = $size['mime-type'];
            $this->generate_webp( $filepath, $mime_type, $attachment_id );
        }

        $this->generate_webp( wp_get_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . $metadata['file'], $mime_type, $attachment_id );
        
        return $metadata;
    }

    protected function generate_webp( $filepath, $file_type, $attachment_id ) {
        $handler = $this->get_handler();
    
        $result = $handler::convert( 
            'webp', 
            [
                'File' => $filepath,
            ], 
            'jpg'
        );
        
        $result->saveFiles( $filepath . '.webp' );
    }
}