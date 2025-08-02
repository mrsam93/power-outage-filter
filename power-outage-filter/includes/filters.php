<?php
defined('ABSPATH') || exit;

// Get the selected theme mode, defaulting to 'standard'
$theme_mode = get_option('poa_theme_mode', 'standard');

/**
 * Standard filtering function for default WooCommerce themes.
 * Hooks into 'woocommerce_product_query'.
 */
function poa_standard_filter_products($q) {
    if (get_option('poa_mode_active') == '1') {
        $tax_query = $q->get('tax_query');
        if (!is_array($tax_query)) {
            $tax_query = [];
        }
        $tax_query[] = [
            'taxonomy' => 'power_outage_availability',
            'field'    => 'slug',
            'terms'    => 'yes',
            'operator' => 'IN',
        ];
        $tax_query['relation'] = 'AND';
        $q->set('tax_query', $tax_query);
    }
}

/**
 * Reyhoon theme specific filtering function.
 * Hooks into 'reyhoon_get_products_args'.
 */
function poa_reyhoon_filter_products($args) {
    if (get_option('poa_mode_active') == '1') {
        if (!isset($args['tax_query']) || !is_array($args['tax_query'])) {
            $args['tax_query'] = [];
        }
        $args['tax_query'][] = [
            'taxonomy' => 'power_outage_availability',
            'field'    => 'slug',
            'terms'    => 'yes',
            'operator' => 'IN',
        ];
    }
    return $args;
}

// Conditionally apply the correct filter based on the theme mode setting
if ($theme_mode === 'reyhoon') {
    add_filter('reyhoon_get_products_args', 'poa_reyhoon_filter_products', 10, 1);
} else {
    add_action('woocommerce_product_query', 'poa_standard_filter_products', 10, 1);
}


// This function is theme-independent and can remain as is.
// It shows a notice on the single product page.
function poa_modify_single_product_display() {
    if (is_product() && get_option('poa_mode_active')) {
        global $post;
        if (!has_term('yes', 'power_outage_availability', $post)) {
            add_filter('woocommerce_is_purchasable', '__return_false'); // Disable Add to Cart
            add_action('woocommerce_single_product_summary', function () {
                echo '<div class="woocommerce-message poa-warning-message" style="border:1px solid red; padding:10px; color:red; margin-bottom:15px;">
                        این محصول در زمان قطعی برق قابل ارائه نمی‌باشد.
                      </div>';
            }, 5);
        }
    }
}
add_action('template_redirect', 'poa_modify_single_product_display');
