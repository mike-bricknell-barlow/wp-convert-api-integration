<?php

namespace WPCAI;

class WPConvertApiIntegrationAdmin {
    function __construct () {
        add_action( 'admin_menu', [ $this, 'register_admin_menu_page' ] );
        add_action( 'register_settings', [ $this, 'register_settings' ] );
        add_action( 'admin_post_update_settings', [ $this, 'update_settings' ] );
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
            $field_value = sanitize_text_field( $_POST[ $field['id'] ] );
            update_option( $field[ 'id' ], $field_value );
        }

        wp_redirect( get_admin_url( null, 'options-general.php?page=wp-convert-api-integration' ) );
        exit();
    }
}