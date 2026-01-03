<?php
/*
 Plugin Name: Chatikonix Customizable Livechat Widget Icon
 Plugin URI: https://raisul.dev/projects/customizable-livechat-widget-icon-for-wordpress
 Description: Lightweight, highly customizable floating social media live chat widget with responsive positioning for seamless interaction across all devices.
 Version: 1.0.3
 Author: Raisul Islam Shagor
 Author URI: https://raisul.dev
 Requires at least: 6.0
 Tested up to: 6.9
 Requires PHP: 7.4
 License: GPLv3 or later
 License URI: https://www.gnu.org/licenses/gpl-3.0.html
 Contributors: shagor447
 Text Domain: chatikonix-customizable-livechat-widget-icon
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';

/**
 * Helper Function: Convert HEX to RGB
 */
function rslclwifw_hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );
    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    return "$r, $g, $b";
}

/**
 * Enqueue Scripts & Add Dynamic Inline Styles
 */
function rslclwifw_enqueue_scripts() {
    wp_enqueue_style( 'rslclwifw-style', plugin_dir_url( __FILE__ ) . 'includes/style.css', array(), '1.0.3' );
    wp_enqueue_script( 'rslclwifw-frontend', plugin_dir_url( __FILE__ ) . 'includes/frontend.js', array(), '1.0.3', true );
    $options = get_option( 'rslclwifw_options' );
    
    // Defaults with Sanitization
    $bg_color_raw = ( isset( $options['bg_color'] ) && ! empty( $options['bg_color'] ) ) ? $options['bg_color'] : '#DC143C';
    $bg_color     = sanitize_hex_color( $bg_color_raw );
    
    $pos_align_raw = isset( $options['position'] ) ? $options['position'] : 'right';
    $pos_align     = ( $pos_align_raw === 'left' ) ? 'left' : 'right';
    
    $bg_rgb = rslclwifw_hex2rgb( $bg_color );

    // Helper for offsets (Ensuring Integers)
    $get_val = function($opt, $key, $def) {
        return ( isset( $opt[$key] ) && $opt[$key] !== '' ) ? intval($opt[$key]) : $def;
    };

    $d_bot = $get_val($options, 'desktop_bottom', 20); 
    $d_side = $get_val($options, 'desktop_side', 20);
    $l_bot = $get_val($options, 'laptop_bottom', 20); 
    $l_side = $get_val($options, 'laptop_side', 20);
    $t_bot = $get_val($options, 'tablet_bottom', 80); 
    $t_side = $get_val($options, 'tablet_side', 15);
    $p_bot = $get_val($options, 'phone_bottom', 90); 
    $p_side = $get_val($options, 'phone_side', 15);

    // Generate CSS String with Late Escaping
    $custom_css = "
        #rslclwifw-livechat-wrapper {
            --rslclwifw-bg: " . esc_attr( $bg_color ) . ";
            --rslclwifw-bg-rgb: " . esc_attr( $bg_rgb ) . ";
            " . esc_attr( $pos_align ) . ": var(--rsl-side);
            bottom: var(--rsl-bottom);
        }
        :root { --rsl-bottom: " . intval( $d_bot ) . "px; --rsl-side: " . intval( $d_side ) . "px; }
        @media (max-width: 1366px) { :root { --rsl-bottom: " . intval( $l_bot ) . "px; --rsl-side: " . intval( $l_side ) . "px; } }
        @media (max-width: 1024px) { :root { --rsl-bottom: " . intval( $t_bot ) . "px; --rsl-side: " . intval( $t_side ) . "px; } }
        @media (max-width: 767px) { :root { --rsl-bottom: " . intval( $p_bot ) . "px; --rsl-side: " . intval( $p_side ) . "px; } }
    ";

    // Attach Dynamic CSS safely
    wp_add_inline_style( 'rslclwifw-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'rslclwifw_enqueue_scripts' );

/**
 * Render Frontend HTML
 */
function rslclwifw_display_chat() {
    $options = get_option( 'rslclwifw_options' );
    
    if ( isset( $options['main_icon_url'] ) && ! empty( $options['main_icon_url'] ) ) {
        $icon_url = $options['main_icon_url'];
    } else {
        $icon_url = plugin_dir_url( __FILE__ ) . 'includes/chat-icon.png';
    }

    $base_url = plugin_dir_url( __FILE__ ) . 'includes/';
    $url_mess = $base_url . 'messenger-icon.png';
    $url_wats = $base_url . 'whatsapp-icon.png';
    $url_tele = $base_url . 'telegram-icon.png';
    $url_phon = $base_url . 'phone-icon.png';

    ?>
    <div id="rslclwifw-livechat-wrapper">
        <div class="rslclwifw-social-links">
            <?php if ( !empty( $options['messenger_link'] ) ) : ?>
                <a href="<?php echo esc_url($options['messenger_link']); ?>" target="_blank" class="rslclwifw-item rslclwifw-messenger" title="<?php esc_attr_e('Messenger', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                    <img src="<?php echo esc_url($url_mess); ?>" alt="<?php esc_attr_e('Messenger', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                </a>
            <?php endif; ?>

            <?php if ( !empty( $options['whatsapp_link'] ) ) : ?>
                <a href="<?php echo esc_url($options['whatsapp_link']); ?>" target="_blank" class="rslclwifw-item rslclwifw-whatsapp" title="<?php esc_attr_e('WhatsApp', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                    <img src="<?php echo esc_url($url_wats); ?>" alt="<?php esc_attr_e('WhatsApp', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                </a>
            <?php endif; ?>

            <?php if ( !empty( $options['telegram_link'] ) ) : ?>
                <a href="<?php echo esc_url($options['telegram_link']); ?>" target="_blank" class="rslclwifw-item rslclwifw-telegram" title="<?php esc_attr_e('Telegram', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                    <img src="<?php echo esc_url($url_tele); ?>" alt="<?php esc_attr_e('Telegram', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                </a>
            <?php endif; ?>

            <?php if ( !empty( $options['phone_link'] ) ) : ?>
                <a href="tel:<?php echo esc_attr($options['phone_link']); ?>" class="rslclwifw-item rslclwifw-phone" title="<?php esc_attr_e('Call Us', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                    <img src="<?php echo esc_url($url_phon); ?>" alt="<?php esc_attr_e('Phone', 'chatikonix-customizable-livechat-widget-icon'); ?>">
                </a>
            <?php endif; ?>
        </div>
        <div class="rslclwifw-main-btn">
            <img src="<?php echo esc_url($icon_url); ?>" alt="<?php esc_attr_e('Chat', 'chatikonix-customizable-livechat-widget-icon'); ?>" class="rsl-custom-img">
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'rslclwifw_display_chat' );
?>