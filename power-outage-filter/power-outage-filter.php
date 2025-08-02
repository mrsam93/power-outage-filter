<?php
/*
Plugin Name: فیلتر محصولات در زمان قطعی برق
Description: فقط محصولات انتخاب‌شده را در زمان قطعی برق نمایش دهید.
Version: 1.0
Author: Erfan Hosseinpour
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
