<?php

namespace WPCAI;

class WPConvertApiIntegrationAdmin {
    function __construct () {
        add_action( 'admin_menu', [ $this, 'register_admin_menu_page' ] );
        add_action( 'register_settings', [ $this, 'register_settings' ] );
        add_action( 'admin_post_update_settings', [ $this, 'update_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
    }
    
    public function enqueue() {
        if( ! isset( $_GET['page'] ) || $_GET['page'] != 'wp-convert-api-integration' ) {
            return;
        }
        
        wp_enqueue_style(
            'wpcai-admin-styling',
            WP_CONVERT_API_INTEGRATION_PLUGIN_DIR_URL . '/assets/admin-css.css',
            [],
            filemtime( WP_CONVERT_API_INTEGRATION_PLUGIN_DIR_PATH . '/assets/admin-css.css' )
        );
    }

    private function get_options_fields() {
        return [
            [
                'label' => 'API Secret',
                'type' => 'password',
                'id' => 'wp-convert-api-integration-api-secret',
                'value' => get_option ( 'wp-convert-api-integration-api-secret' ),
                'description' => '',
                'placeholder' => '',
            ],
            [
                'label' => 'File types',
                'type' => 'checkbox',
                'id' => 'wp-convert-api-integration-allowed-filetypes',
                'value' => get_option ( 'wp-convert-api-integration-allowed-filetypes' ),
                'description' => 'Select the filetypes that you\'d like to convert uploaded files to. The compress option is used to make the filesize smaller while retaining the same filetype.',
                'placeholder' => '',
            ],
        ];
    }

    public static function get_filetype_options() {
        return [
            'jpg',
            'pdf',
            'pdfa',
            'png',
            'svg',
            'tiff',
            'webp',
            'gif',
            'compress',
        ];
    }

    public function register_settings() {
        $fields = $this->get_options_fields();
        $option_group = 'wp-convert-api-integration-api-secret-options-group';
        
        foreach ( $fields as $field ) {
            register_setting (
                $option_group,
                $field['id'],
                array ()
            );
        }
    }

    public function register_admin_menu_page() {
        add_submenu_page(
            'options-general.php',
            'ConvertAPI Integration for WordPress',
            'ConvertAPI Integration for WordPress',
            'manage_options',
            'wp-convert-api-integration',
            [ $this, 'display_admin_menu_page' ]
        );
    }

    public function display_admin_menu_page() {
        $fields = $this->get_options_fields();
        include WP_CONVERT_API_INTEGRATION_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'admin-menu.php';
    }

    public function update_settings() {
        $fields = $this->get_options_fields();

        foreach ( $fields as $field ) {     
            $field_value = $_POST[ $field['id'] ];
            if( ! is_array( $field_value ) ) {
                $field_value = sanitize_text_field( $field_value );
            }    
            
            if( $field_value == '' ) {
                continue;
            }
            
            update_option( $field[ 'id' ], $field_value );
        }

        wp_redirect( get_admin_url( null, 'options-general.php?page=wp-convert-api-integration' ) );
        exit();
    }
}