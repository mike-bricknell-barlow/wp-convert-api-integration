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

                            case 'checkbox': 
                                echo sprintf( 
                                    '<th><label for="%s">%s</label></th>',
                                    esc_html( $field['id'] ),
                                    esc_html( $field['label'] ),
                                );
                                echo sprintf( '<td><p>%s</p>', esc_html( $field['description'] ) );

                                foreach( WPCAI\WPConvertApiIntegrationAdmin::get_filetype_options() as $filetype ) {
                                    echo sprintf(
                                        '<label for="%s">%s</label><input type="checkbox" value="%s" name="%s[]" id="%s" %s />',
                                        $field['id'] . '-' . $filetype,
                                        $filetype,
                                        $filetype,
                                        $field['id'],
                                        $field['id'] . '-' . $filetype,
                                        ( in_array( $filetype, $field['value'] ) ) ? 'checked' : ''
                                    );
                                }

                                echo '</td>';
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