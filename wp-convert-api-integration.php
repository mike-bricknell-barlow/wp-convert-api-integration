<?php

/**
 * Plugin Name:       ConvertAPI Integration for WordPress
 * Plugin URI:        
 * Description:       Integrates WordPress with the ConvertAPI service, and convert/compress images or other files on the fly.
 * Version:           1.0.0
 * Requires at least: 5.0.0
 * Requires PHP:      7.0
 * Author:            Mike Bricknell-Barlow
 * Author URI:        https://bricknellbarlow.co.uk
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-convert-api-integration
*/

define( 'WP_CONVERT_API_INTEGRATION_VERSION', '1.0.0' );
define( 'WP_CONVERT_API_INTEGRATION_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_CONVERT_API_INTEGRATION_PLUGIN_DIR_PATH', dirname( __FILE__ ) );

require_once __DIR__ . '/includes' . DIRECTORY_SEPARATOR . 'class-wp-convert-api-integration.php';
require_once __DIR__ . '/includes' . DIRECTORY_SEPARATOR . 'class-wp-convert-api-integration-admin.php';
require_once __DIR__ . '/vendor/autoload.php';

new WPCAI\WPConvertApiIntegrationAdmin();
new WPCAI\WPConvertApiIntegration();