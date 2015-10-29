<?php
// check if unistall/delete in not called from wordpress and if is the case, exit
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
    exit();

// else delete the options saved in the option table
delete_option($tfa_options_arr);
?>
