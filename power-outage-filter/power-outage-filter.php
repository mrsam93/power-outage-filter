<?php
/**
 * Plugin Name: فیلتر محصولات در زمان قطعی برق
 * Plugin URI:  https://github.com/mrsam93/power-outage-filter
 * Description: فقط محصولات انتخاب‌شده را در زمان قطعی برق نمایش می دهد.
 * Version:     1.1.0
 * Author:      SaranHosting (Saman Ghadri)
 * Author URI:  https://saranhosting.com
 * License:     GPL-2.0+
 * Text Domain: power-outage-filter
 */

defined('ABSPATH') || exit;

add_action('wp_head', function () {
    ?>
    <style>
        .woocommerce-message.poa-warning-message::before {
            content: none;
        }
    </style>
    <?php
});




// Include files
require_once plugin_dir_path(__FILE__) . 'includes/taxonomy.php';
require_once plugin_dir_path(__FILE__) . 'includes/filters.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-ui.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';


