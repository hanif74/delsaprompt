<?php

function delsaprompt_add_settings_menu() {
    add_options_page(
        'delsaprompt Settings',
        'delsaprompt',
        'manage_options',
        'delsaprompt-settings',
        'delsaprompt_render_settings_page'
    );
}
add_action('admin_menu', 'delsaprompt_add_settings_menu');


function delsaprompt_register_settings() {
    register_setting('delsaprompt_settings_group', 'delsaprompt_api_key');
}
add_action('admin_init', 'delsaprompt_register_settings');

function delsaprompt_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>delsaprompt Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('delsaprompt_settings_group'); ?>
            <?php do_settings_sections('delsaprompt_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">OpenAI API Key</th>
                    <td>
                        <input type="text" name="delsaprompt_api_key" value="<?php echo esc_attr(get_option('delsaprompt_api_key')); ?>" size="60" />
                        <p class="description">Your key is used to securely connect to OpenAI. It is stored in the database.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
