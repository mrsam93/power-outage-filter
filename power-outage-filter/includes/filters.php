<?php
defined('ABSPATH') || exit;


add_action('woocommerce_product_query', 'poa_filter_available_products');

function poa_filter_available_products($q)
{
    if (get_option('poa_mode_active') == '1') {
        $tax_query = $q->get('tax_query');
        if (!is_array($tax_query)) {
            $tax_query = [];
        }

        // Add our specific query
        $tax_query[] = [
            'taxonomy' => 'power_outage_availability',
            'field'    => 'slug',
            'terms'    => 'yes',
            'operator' => 'IN',
        ];

        // Explicitly set the relation to AND to avoid conflicts
        $tax_query['relation'] = 'AND';

        $q->set('tax_query', $tax_query);
    }
}

// Show product page with notice instead of blocking it
function poa_modify_single_product_display()
{
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
