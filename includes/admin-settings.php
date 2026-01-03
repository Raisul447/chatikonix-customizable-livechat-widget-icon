<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Menu Registration
function rslclwifw_add_admin_menu() {
    add_management_page( 
        __( 'Livechat Manage', 'chatikonix-customizable-livechat-widget-icon' ), 
        __( 'Livechat Manage', 'chatikonix-customizable-livechat-widget-icon' ), 
        'manage_options', 
        'rslclwifw-livechat', 
        'rslclwifw_settings_page_html' 
    );
}
add_action( 'admin_menu', 'rslclwifw_add_admin_menu' );

// Settings Link
function rslclwifw_add_plugin_link( $links ) {
    $settings_link = '<a href="tools.php?page=rslclwifw-livechat">' . __( 'Settings', 'chatikonix-customizable-livechat-widget-icon' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_chatikonix-customizable-livechat-widget-icon/chatikonix-customizable-livechat-widget-icon.php', 'rslclwifw_add_plugin_link' );

// Register Settings
function rslclwifw_register_settings() {
    register_setting( 'rslclwifw_settings_group', 'rslclwifw_options', 'rslclwifw_sanitize_options' );
}
add_action( 'admin_init', 'rslclwifw_register_settings' );

// Sanitize Inputs
function rslclwifw_sanitize_options( $input ) {
    $new_input = array();
    $new_input['main_icon_url'] = sanitize_text_field( $input['main_icon_url'] );
    $new_input['bg_color']      = sanitize_hex_color( $input['bg_color'] );
    $new_input['position']      = sanitize_key( $input['position'] );

    $new_input['desktop_bottom'] = intval( $input['desktop_bottom'] );
    $new_input['desktop_side']   = intval( $input['desktop_side'] );
    $new_input['laptop_bottom']  = intval( $input['laptop_bottom'] );
    $new_input['laptop_side']    = intval( $input['laptop_side'] );
    $new_input['tablet_bottom']  = intval( $input['tablet_bottom'] );
    $new_input['tablet_side']    = intval( $input['tablet_side'] );
    $new_input['phone_bottom']   = intval( $input['phone_bottom'] );
    $new_input['phone_side']     = intval( $input['phone_side'] );
    
    $new_input['messenger_link'] = esc_url_raw( $input['messenger_link'] );
    $new_input['whatsapp_link']  = esc_url_raw( $input['whatsapp_link'] );
    $new_input['telegram_link']  = esc_url_raw( $input['telegram_link'] );
    $new_input['phone_link']     = sanitize_text_field( $input['phone_link'] );

    return $new_input;
}

// Enqueue Admin Scripts
function rslclwifw_admin_scripts( $hook ) {
    if ( 'tools_page_rslclwifw-livechat' !== $hook ) { return; }
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'rslclwifw-admin-style', plugin_dir_url( __FILE__ ) . 'admin-style.css', array(), '1.0.2' );
    wp_enqueue_script( 'rslclwifw-admin-script', plugin_dir_url( __FILE__ ) . 'admin.js', array( 'wp-color-picker' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'rslclwifw_admin_scripts' );

// Helper function
function rslclwifw_get_opt_val( $opt, $key, $default ) {
    return ( isset( $opt[ $key ] ) && $opt[ $key ] !== '' ) ? $opt[ $key ] : $default;
}

// Settings Page HTML
function rslclwifw_settings_page_html() {
    $options = get_option( 'rslclwifw_options' );
    
    $bg_color = ( isset( $options['bg_color'] ) && ! empty( $options['bg_color'] ) ) ? $options['bg_color'] : '#DC143C';
    $position = isset( $options['position'] ) ? $options['position'] : 'right';
    
    $d_bot = rslclwifw_get_opt_val($options, 'desktop_bottom', 20); 
    $d_side = rslclwifw_get_opt_val($options, 'desktop_side', 20);
    $l_bot = rslclwifw_get_opt_val($options, 'laptop_bottom', 20); 
    $l_side = rslclwifw_get_opt_val($options, 'laptop_side', 20);
    $t_bot = rslclwifw_get_opt_val($options, 'tablet_bottom', 80); 
    $t_side = rslclwifw_get_opt_val($options, 'tablet_side', 15);
    $p_bot = rslclwifw_get_opt_val($options, 'phone_bottom', 90); 
    $p_side = rslclwifw_get_opt_val($options, 'phone_side', 15);

    $base_url = plugin_dir_url( __FILE__ ); 
    $ico_mess = $base_url . 'messenger-icon.png';
    $ico_wats = $base_url . 'whatsapp-icon.png';
    $ico_tele = $base_url . 'telegram-icon.png';
    $ico_phon = $base_url . 'phone-icon.png';
    ?>
    <div class="rsl-admin-wrap">
        <form method="post" action="options.php">
            <?php settings_fields( 'rslclwifw_settings_group' ); ?>
            <div class="rsl-header"><h1><?php esc_html_e( 'Livechat Settings', 'chatikonix-customizable-livechat-widget-icon' ); ?></h1></div>

            <div class="rsl-grid-2">
                <div class="rsl-card">
                    <h2 class="rsl-card-title"><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e( 'Appearance', 'chatikonix-customizable-livechat-widget-icon' ); ?></h2>
                    <div class="rsl-form-group">
                        <label><?php esc_html_e( 'Main Chat Icon', 'chatikonix-customizable-livechat-widget-icon' ); ?></label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" name="rslclwifw_options[main_icon_url]" id="rslclwifw_main_icon_url" value="<?php echo esc_attr( isset($options['main_icon_url']) ? $options['main_icon_url'] : '' ); ?>" class="rsl-input-full" placeholder="http://...">
                            <input type="button" class="button button-secondary" value="<?php esc_attr_e( 'Upload', 'chatikonix-customizable-livechat-widget-icon' ); ?>" id="rslclwifw_upload_icon_btn">
                        </div>
                        <p class="rsl-helper"><?php esc_html_e( 'Upload PNG/SVG. Default icon used if empty.', 'chatikonix-customizable-livechat-widget-icon' ); ?></p>
                    </div>
                    <div class="rsl-flex-row">
                        <div class="rsl-flex-col">
                            <div class="rsl-form-group">
                                <label><?php esc_html_e( 'Background Color', 'chatikonix-customizable-livechat-widget-icon' ); ?></label>
                                <input type="text" name="rslclwifw_options[bg_color]" value="<?php echo esc_attr( $bg_color ); ?>" class="rslclwifw-color-field">
                            </div>
                        </div>
                        <div class="rsl-flex-col">
                            <div class="rsl-form-group">
                                <label><?php esc_html_e( 'Alignment', 'chatikonix-customizable-livechat-widget-icon' ); ?></label>
                                <select name="rslclwifw_options[position]" class="rsl-input-full">
                                    <option value="right" <?php selected( $position, 'right' ); ?>><?php esc_html_e( 'Right', 'chatikonix-customizable-livechat-widget-icon' ); ?></option>
                                    <option value="left" <?php selected( $position, 'left' ); ?>><?php esc_html_e( 'Left', 'chatikonix-customizable-livechat-widget-icon' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rsl-card">
                    <h2 class="rsl-card-title"><span class="dashicons dashicons-share"></span> <?php esc_html_e( 'Social Links', 'chatikonix-customizable-livechat-widget-icon' ); ?></h2>
                    <div class="rsl-social-list">
                        <div class="rsl-social-row">
                            <div class="rsl-social-icon" style="background:#0084FF;"><img src="<?php echo esc_url( $ico_mess ); ?>"></div>
                            <div class="rsl-input-wrap"><input type="url" name="rslclwifw_options[messenger_link]" value="<?php echo esc_attr( isset($options['messenger_link']) ? $options['messenger_link'] : '' ); ?>" placeholder="Messenger URL"></div>
                        </div>
                        <div class="rsl-social-row">
                            <div class="rsl-social-icon" style="background:#25D366;"><img src="<?php echo esc_url( $ico_wats ); ?>"></div>
                            <div class="rsl-input-wrap"><input type="url" name="rslclwifw_options[whatsapp_link]" value="<?php echo esc_attr( isset($options['whatsapp_link']) ? $options['whatsapp_link'] : '' ); ?>" placeholder="WhatsApp URL"></div>
                        </div>
                        <div class="rsl-social-row">
                            <div class="rsl-social-icon" style="background:#0088cc;"><img src="<?php echo esc_url( $ico_tele ); ?>"></div>
                            <div class="rsl-input-wrap"><input type="url" name="rslclwifw_options[telegram_link]" value="<?php echo esc_attr( isset($options['telegram_link']) ? $options['telegram_link'] : '' ); ?>" placeholder="Telegram URL"></div>
                        </div>
                        <div class="rsl-social-row">
                            <div class="rsl-social-icon" style="background:#34495e;"><img src="<?php echo esc_url( $ico_phon ); ?>"></div>
                            <div class="rsl-input-wrap"><input type="text" name="rslclwifw_options[phone_link]" value="<?php echo esc_attr( isset($options['phone_link']) ? $options['phone_link'] : '' ); ?>" placeholder="Phone Number"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rsl-card" style="margin-bottom: 30px;">
                <h2 class="rsl-card-title"><span class="dashicons dashicons-smartphone"></span> <?php esc_html_e( 'Responsive Widget Positioning (Offsets)', 'chatikonix-customizable-livechat-widget-icon' ); ?></h2>
                <div class="rsl-resp-grid">
                    <div class="rsl-resp-card">
                        <span class="dashicons dashicons-desktop" style="font-size: 24px; color: #ccc;"></span><h4>Desktop</h4>
                        <p style="font-size:11px; color:#888; margin-top:-8px; margin-bottom:12px;">&gt; 1366px</p>
                        <div class="rsl-resp-input-group"><span>Bottom:</span> <input type="number" name="rslclwifw_options[desktop_bottom]" value="<?php echo esc_attr($d_bot); ?>"></div>
                        <div class="rsl-resp-input-group"><span>Side:</span> <input type="number" name="rslclwifw_options[desktop_side]" value="<?php echo esc_attr($d_side); ?>"></div>
                    </div>
                    <div class="rsl-resp-card">
                        <span class="dashicons dashicons-laptop" style="font-size: 24px; color: #ccc;"></span><h4>Laptop</h4>
                        <p style="font-size:11px; color:#888; margin-top:-8px; margin-bottom:12px;">1025px - 1366px</p>
                        <div class="rsl-resp-input-group"><span>Bottom:</span> <input type="number" name="rslclwifw_options[laptop_bottom]" value="<?php echo esc_attr($l_bot); ?>"></div>
                        <div class="rsl-resp-input-group"><span>Side:</span> <input type="number" name="rslclwifw_options[laptop_side]" value="<?php echo esc_attr($l_side); ?>"></div>
                    </div>
                    <div class="rsl-resp-card">
                        <span class="dashicons dashicons-tablet" style="font-size: 24px; color: #ccc;"></span><h4>Tablet</h4>
                        <p style="font-size:11px; color:#888; margin-top:-8px; margin-bottom:12px;">768px - 1024px</p>
                        <div class="rsl-resp-input-group"><span>Bottom:</span> <input type="number" name="rslclwifw_options[tablet_bottom]" value="<?php echo esc_attr($t_bot); ?>"></div>
                        <div class="rsl-resp-input-group"><span>Side:</span> <input type="number" name="rslclwifw_options[tablet_side]" value="<?php echo esc_attr($t_side); ?>"></div>
                    </div>
                    <div class="rsl-resp-card">
                        <span class="dashicons dashicons-smartphone" style="font-size: 24px; color: #ccc;"></span><h4>Phone</h4>
                        <p style="font-size:11px; color:#888; margin-top:-8px; margin-bottom:12px;">&lt; 768px</p>
                        <div class="rsl-resp-input-group"><span>Bottom:</span> <input type="number" name="rslclwifw_options[phone_bottom]" value="<?php echo esc_attr($p_bot); ?>"></div>
                        <div class="rsl-resp-input-group"><span>Side:</span> <input type="number" name="rslclwifw_options[phone_side]" value="<?php echo esc_attr($p_side); ?>"></div>
                    </div>
                </div>
            </div>

            <div class="rsl-save-bar"><span style="color:#666;"><?php esc_html_e( 'Make sure to save your changes.', 'chatikonix-customizable-livechat-widget-icon' ); ?></span><?php submit_button('Save Settings', 'primary large', 'submit', false); ?></div>
        </form>
    </div>
    <?php
}
?>