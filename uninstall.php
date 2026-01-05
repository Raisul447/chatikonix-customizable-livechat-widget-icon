<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Customizable_Livechat_Widget_Icon
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'rslclwifw_options' );

?>
