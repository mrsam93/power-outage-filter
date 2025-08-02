<?php
defined('ABSPATH') || exit;

function poa_register_taxonomy()
{
    register_taxonomy(
        'power_outage_availability',
        'product',
        [
            'label' => 'موجودیت در زمان قطعی برق',
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'hierarchical' => false,
            'rewrite' => false,
        ]
    );
}
add_action('init', 'poa_register_taxonomy');
