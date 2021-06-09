<div class="wrap">
    <h1>ConvertAPI Integration for WordPress - Settings</h1>

    <?php do_action( 'show_pre_plugin_messages' ) ?>

    <form method="post" action="<?php echo admin_url ( 'admin-post.php' ) ?>"> 
        <?php settings_fields( 'wp-convert-api-integration-api-secret-options-group' ); ?>
        <input type="hidden" name="action" value="update_settings">
        <table class="form-table">
            <tbody>
                <?php 
                
                foreach ( $fields as $field ) {
                    echo '<tr>';
                        switch ( $field['type'] ) {
                            case 'password':
                                ?>

                                <th><label for="<?php echo esc_html( $field['id'] ) ?>"><?php echo esc_html( $field['label'] ) ?></label></th>
                                <td>
                                    <input type="password" id="<?php echo esc_html( $field['id'] ) ?>" name="<?php echo esc_html( $field['id'] ) ?>" />
                                    <?php if ( $field['description'] ): ?>
                                        <p><?php echo esc_html( $field['description'] ) ?></p>
                                    <?php endif; ?>
                                </td>

                                <?php
                                break;
                        }
                    echo '</tr>';
                }
                
                ?>
            </tbody>
        </table>   
        <?php submit_button(); ?> 
    </form>
</div>