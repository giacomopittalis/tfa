<?php
/*
  Plugin Name: WordPress Images Title from Alt
  Description: SEO Tool to automatically set the title attribute equal to the alt attribute when title is missing in img tags
  Version: 1.0
  Author: Giacomo Pittalis
 */


// Hook activation to call our install function
register_activation_hook(__FILE__, 'tfa_install');

// function to create the copyright option (we are creating an array in case we will add any other option)
function tfa_install() {
    //setup default option value (empty on activation)
    $tfa_options_arr = array(
        'copyright' => ''
    );
    //save our default option values
    update_option('tfa_options', $tfa_options_arr);
}

// hook the admin_menu to create the settings menu
add_action('admin_menu', 'tfa_create_menu');

// funcion to create settings menu
function tfa_create_menu() {
    //create a top level menu
    add_menu_page('Title from Alt Settings', 'TFA Settings', 'manage_options', 'tfa_settings', 'tfa_main_settings_page');
    // call register settings functions
    add_action('admin_init', 'tfa_register_settings');
}

function tfa_register_settings() {
    // register our settings
    register_setting('tfa-settings-group', 'tfa_options', 'tfa_sanitize_options');
}

// build  the option page
function tfa_main_settings_page() {
    ?>
    <div class="wrap">
        <h2><?php _e('WordPress Images Title from Alt Settings', 'tfa-plugin') ?></h2>

        <form method="post" action="options.php">
            <?php settings_fields('tfa-settings-group'); ?>
            <?php $tfa_options = get_option('tfa_options'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Copyright', 'tfa-plugin') ?><br /></th>
                    <td><input type="text" name="tfa_options[copyright]" value="<?php echo esc_attr($tfa_options[copyright]); ?>" size="50" maxlength="50" /></td>
                </tr>
                <tr><td colspan="2"><b>Notice:</b> this text is appended to the Title attribute. <br />So, if the copied Alt attribute is "Image of the sun", and you set here the value to "Copyright: mysite",<br /> the final result will be: <br />
                        &lt;img src="img_sun.jpg" alt="Image of the sun" title="Image of the sun Copyright: mysite"&gt; </td></tr>
            </table>

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'tfa-plugin'); ?>" />
            </p>

        </form>
    </div>
    <?php
}

// sanitize option inserted by user
function tfa_sanitize_options($options) {
    $options['copyright'] = (!empty($options['copyright']) ) ? sanitize_text_field($options['copyright']) : '';
    return $options;
}

// hook the footer to add the jquery we use to create the title attribute
add_action('wp_footer', 'img_title_from_alt');

// function to create the title attribute
function img_title_from_alt() {
    // retrieve the plugin option's array
    $tfa_options = get_option('tfa_options');
    // retrieve the copyright option value
    $cr = $tfa_options[copyright];
    //  If not empty, add a space before
    $cr_with_space = ($cr <> '') ? ' ' . $cr : '';
    ?>
    <script>
        // for each img tag in the page take the alt attribute and set the title same as alt then add the Copyright option
        jQuery('img').each(function() {
            if (jQuery(this).attr('title')) {
                // title already exist. Do nothing
            } else {
                // title does not exist. Copy the Alt in place
                jQuery(this).attr('title', jQuery(this).attr('alt'));
            }

        });
    </script>
    <?php
}
?>
