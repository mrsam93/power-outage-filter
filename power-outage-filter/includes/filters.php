<?php
defined('ABSPATH') || exit;


add_filter('reyhoon_get_products_args', 'poa_filter_available_products', 10, 1);

function poa_filter_available_products($args)
{
    if (get_option('poa_mode_active') == '1') {
        // Make sure tax_query exists as an array
        if (!isset($args['tax_query']) || !is_array($args['tax_query'])) {
            $args['tax_query'] = [];
        }

        $args['tax_query'][] = array(
            'taxonomy' => 'power_outage_availability',
            'field' => 'slug',
            'terms' => 'yes',
            'operator' => 'IN',
        );
    }

    return $args;
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
