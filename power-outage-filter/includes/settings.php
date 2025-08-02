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
    if (isset($_POST['poa_mode_active_nonce']) && wp_verify_nonce($_POST['poa_mode_active_nonce'], 'poa_save_mode')) {
        update_option('poa_mode_active', isset($_POST['poa_mode_active']) ? '1' : '0');
        echo '<div class="updated"><p>تنظیمات ذخیره شد.</p></div>';
    }

    $checked = get_option('poa_mode_active') === '1' ? 'checked' : '';
    ?>

    <div class="wrap">
        <h1>تنظیمات حالت قطعی برق</h1>
        <form method="post">
            <?php wp_nonce_field('poa_save_mode', 'poa_mode_active_nonce'); ?>
            <label class="poa-switch">
                <input type="checkbox" name="poa_mode_active" value="1" <?= esc_attr($checked) ?>>
                <span class="poa-slider round"></span>
            </label>
            <p>فعال‌سازی محدودیت محصولات در زمان قطعی برق</p>
            <p><input type="submit" class="button button-primary" value="ذخیره تنظیمات"></p>
        </form>
    </div>
    <?php
}
