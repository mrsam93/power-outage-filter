<?php
defined('ABSPATH') || exit;

/**
 * Add a new admin menu item
 */
add_action('admin_menu', 'poa_add_admin_menu');
function poa_add_admin_menu()
{
    add_menu_page(
        'وضعیت قطعی برق',          // Page title
        'وضعیت برق',               // Menu title
        'manage_options',          // Capability
        'poa-power-mode',          // Menu slug
        'poa_settings_page_html',  // Callback
        'dashicons-lightbulb',     // Icon
        80                         // Position
    );
}

/**
 * Enqueue toggle style
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_poa-power-mode') {
        wp_enqueue_style('poa-toggle-style', plugin_dir_url(__FILE__) . '/admin.css');
    }
});

/**
 * Render the settings page
 */
function poa_settings_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if (isset($_POST['poa_settings_nonce']) && wp_verify_nonce($_POST['poa_settings_nonce'], 'poa_save_settings')) {
        // Save the power mode toggle
        update_option('poa_mode_active', isset($_POST['poa_mode_active']) ? '1' : '0');

        // Save the new theme mode setting
        if (isset($_POST['poa_theme_mode']) && in_array($_POST['poa_theme_mode'], ['standard', 'reyhoon'])) {
            update_option('poa_theme_mode', sanitize_text_field($_POST['poa_theme_mode']));
        }

        echo '<div class="updated"><p>تنظیمات ذخیره شد.</p></div>';
    }

    $power_mode_checked = get_option('poa_mode_active') === '1' ? 'checked' : '';
    $current_theme_mode = get_option('poa_theme_mode', 'standard'); // Default to 'standard'
    ?>

    <div class="wrap">
        <h1>تنظیمات حالت قطعی برق</h1>
        <form method="post">
            <?php wp_nonce_field('poa_save_settings', 'poa_settings_nonce'); ?>

            <h2>حالت قطعی برق</h2>
            <label class="poa-switch">
                <input type="checkbox" name="poa_mode_active" value="1" <?= esc_attr($power_mode_checked) ?>>
                <span class="poa-slider round"></span>
            </label>
            <p>فعال‌سازی محدودیت محصولات در زمان قطعی برق</p>

            <hr>

            <h2>سازگاری با قالب</h2>
            <p>انتخاب کنید که افزونه با کدام نوع قالب کار کند.</p>
            <fieldset>
                <label>
                    <input type="radio" name="poa_theme_mode" value="standard" <?php checked($current_theme_mode, 'standard'); ?>>
                    قالب استاندارد (پیش‌فرض)
                </label>
                <br>
                <label>
                    <input type="radio" name="poa_theme_mode" value="reyhoon" <?php checked($current_theme_mode, 'reyhoon'); ?>>
                    قالب ریحون
                </label>
            </fieldset>

            <p style="margin-top: 20px;"><input type="submit" class="button button-primary" value="ذخیره تنظیمات"></p>
        </form>
    </div>
    <?php
}
